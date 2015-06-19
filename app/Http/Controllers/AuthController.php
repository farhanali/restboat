<?php namespace Restboat\Http\Controllers;

use Illuminate\Http\Request;
use Restboat\Services\AuthListener;
use Restboat\Services\AuthService;

class AuthController extends Controller implements AuthListener
{

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Restboat\Services\AuthService $authService
     * @param null $provider
     * @return \Illuminate\Routing\Redirector|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function login(Request $request, AuthService $authService, $provider = null)
    {
        return $authService->execute($request, $this, $provider);
    }

    /**
     * Logout the current user.
     */
    public function logout()
    {
        \Auth::logout();

        return redirect()->route('index');
    }

    /**
     * When a user has successfully been logged in.
     *
     * @param $user
     * @return \Illuminate\Routing\Redirector
     */
    public function userHasLoggedIn($user)
    {
        return redirect()->route('index');
    }

}