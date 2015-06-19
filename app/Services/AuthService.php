<?php namespace Restboat\Services;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Factory as Socialite;

use Restboat\Repositories\UserRepository;

class AuthService {

    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    private $auth;

    /**
     * @var \Laravel\Socialite\Contracts\Factory
     */
    private $socialite;

    /**
     * @var \Restboat\Repositories\UserRepository
     */
    private $userRepo;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \Laravel\Socialite\Contracts\Factory $socialite
     * @param \Restboat\Repositories\UserRepository $userRepo
     */
    public function __construct(Guard $auth, Socialite $socialite, UserRepository $userRepo)
    {
        $this->auth         = $auth;
        $this->userRepo     = $userRepo;
        $this->socialite    = $socialite;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Restboat\Services\AuthListener $listener
     * @param $provider
     * @return \Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function execute(Request $request, AuthListener $listener, $provider)
    {
        if ( ! $request->has('code'))
        {
            return $this->getAuthorization($provider);
        }

        $user = $this->userRepo->findOrCreate($this->getSocialUser($provider), $provider);

        $this->auth->login($user, true);

        return $listener->userHasLoggedIn($user);
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getAuthorization($provider)
    {
        switch ($provider)
        {
            case 'github' :
                return $this->getGithubAuthorization($provider);

            case 'google' :
                return $this->getGoogleAuthorization($provider);
        }

        return null;
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getGithubAuthorization($provider)
    {
        return $this->socialite->driver($provider)->scopes(['user:email'])->redirect();
    }

    /**
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getGoogleAuthorization($provider)
    {
        return $this->socialite->driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return \Laravel\Socialite\Contracts\User
     */
    private function getSocialUser($provider)
    {
        return $this->socialite->driver($provider)->user();
    }

}