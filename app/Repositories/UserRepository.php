<?php namespace Restboat\Repositories;

use Restboat\Models\User;

class UserRepository extends BaseRepository {

    /**
     * @param User $model
     */
    function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $userData
     * @param $provider
     * @return static
     */
    public function findOrCreate($userData, $provider)
    {
        $user = $this->model->where('email', '=', $userData->email)
            ->first();

        if ( ! $user)
        {
            return $this->create($this->getOptimizedUserData($userData, $provider));
        }

        return $this->updateIfDataChanged($user, $userData, $provider);
    }

    /**
     * @param User $user
     * @param $userData
     * @param $provider
     * @return User
     */
    private function updateIfDataChanged(User $user, $userData, $provider)
    {
        $socialData = $this->getOptimizedUserData($userData, $provider);

        $dbData = array(
            'name'          => $user->name,
            'email'         => $user->email,
            'avatar'        => $user->avatar,
            'provider_id'   => $user->provider_id,
            'provider'      => $user->provider,
        );

        $diff = array_diff($socialData, $dbData);
        if ( ! empty($diff))
        {
            $this->update($user->id, $socialData);
        }

        return $user;
    }

    /**
     * @param $userData
     * @param $provider
     * @return array
     */
    private function getOptimizedUserData($userData, $provider)
    {
        return array(
            'name'          => $userData->name,
            'email'         => $userData->email,
            'avatar'        => $userData->avatar,
            'provider_id'   => $userData->id,
            'provider'      => $provider,
        );
    }

}