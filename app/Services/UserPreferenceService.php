<?php  namespace Restboat\Services;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Restboat\Models\User;
use Restboat\Repositories\UserPreferenceRepository;
use Restboat\Repositories\UserRepository;

class UserPreferenceService {

    /**
     * @var \Illuminate\Auth\Guard
     */
    private $auth;

    /**
     * @var \Restboat\Repositories\UserRepository
     */
    private $userRepo;

    /**
     * @var \Restboat\Repositories\UserPreferenceRepository
     */
    private $preferenceRepo;

    /**
     * @param \Illuminate\Auth\Guard $auth
     * @param \Restboat\Repositories\UserRepository $userRepo
     * @param \Restboat\Repositories\UserPreferenceRepository $preferenceRepo
     */
    public function __construct(
        Guard $auth, UserRepository $userRepo,
        UserPreferenceRepository $preferenceRepo)
    {
        $this->auth             = $auth;
        $this->userRepo         = $userRepo;
        $this->preferenceRepo   = $preferenceRepo;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show()
    {
        $preferences = $this->getUserPreferences($this->auth->user());

        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return view('user.preferences', compact('preferences', 'timezones'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $this->auth->user();

        $this->updatePreferences($request, $user->preferences->id);

        flash()->success('Preferences Saved ..! ');

        return redirect()->route('user.preferences');
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateToken()
    {
        $user = $this->auth->user();

        $this->preferenceRepo->update($user->preferences->id, array(
            'boat_token'    => md5(uniqid(mt_rand(), true)),
        ));

        flash()->success('Security token regenerated ..!');

        return redirect()->route('user.preferences');
    }

    /**
     * @param \Restboat\Models\User $user
     * @return \Restboat\Models\BaseModel
     */
    private function getUserPreferences(User $user)
    {
        if ($user->preferences)
        {
            return $user->preferences;
        }

        $preference = $this->preferenceRepo->create(
            array(
                    'user_id'       => $user->id,
                    'boat_token'    => md5(uniqid(mt_rand(), true)),
                )
        );

        return $this->preferenceRepo->find($preference->id);
    }

    /**
     * Updates timezone, default response and additional security preferences.
     *
     * @param \Illuminate\Http\Request $request
     * @param $preferenceId
     */
    private function updatePreferences(Request $request, $preferenceId)
    {
        $attributes = $request->only(array(
            'user_identifier', 'timezone', 'default_response_status',
            'default_response_type', 'default_response_content'
        ));

        $attributes['boat_token_enable'] = $this->getAdditionalSecurityPreference($request);

        $this->preferenceRepo->update($preferenceId, $attributes);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    private function getAdditionalSecurityPreference(Request $request)
    {
        return $request->has('boat_token_enable');
    }

}