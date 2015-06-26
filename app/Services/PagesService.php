<?php  namespace Restboat\Services; 

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Session\Store;
use Restboat\Models\User;
use Restboat\Repositories\RequestLogRepository;

class PagesService {

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $auth;

    /**
     * @var \Illuminate\Session\Store
     */
    private $sesion;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \Illuminate\Session\Store $session
     */
    function __construct(Guard $auth, Store $session)
    {
        $this->auth     = $auth;
        $this->session  = $session;
    }

    /**
     * Redirect to user home if user has set user_identifier else to user preference page to set one,
     * if user not logged in redirect to public home.
     *
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getIndex()
    {
        $this->session->reflash();

        if ($this->auth->check())
        {
            return $this->hasSetIdentifier()
                ? $this->routeUserHome()
                : $this->routePreference();
        }

        return $this->routePublicHome();
    }

    /**
     * Check whether the logged user has set the user identifier preference.
     *
     * @return bool
     */
    private function hasSetIdentifier()
    {
        $preferences = $this->auth->user()->preferences;

        return $preferences && $preferences->user_identifier;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function routePreference()
    {
        flash()->overlay('Hello, Please set your mock server preferences, don\'t worry, It\'s not too much.. :)' , 'Welcome to RESTBOAT !');
        return redirect()->route('user.preferences');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    private function routeUserHome()
    {
        if ($this->userHasRequestLogs())
        {
            return redirect()->route('history.index');
        }

        return redirect()->route('collections.index');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function routePublicHome()
    {
        return response()->view('home');
    }

    /**
     * @return bool
     */
    private function userHasRequestLogs()
    {
        $user = $this->auth->user();

        return $user->requestLogs->count() > 0;
    }

}