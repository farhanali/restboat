<?php  namespace Restboat\Services; 

use Illuminate\Contracts\Auth\Guard;
use Restboat\Models\User;
use Restboat\Repositories\RequestLogRepository;

class PagesService {

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    function __construct(Guard $auth)
    {
        $this->auth     = $auth;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function getIndex()
    {
        // Show user home if user has set user_identifier
        // else show user preference page to set one
        // If user not logged in show public home.
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