<?php namespace Restboat\Repositories;

use Restboat\Models\UserPreference;

class UserPreferenceRepository extends BaseRepository {

    /**
     * @param UserPreference $model
     */
    function __construct(UserPreference $model)
    {
        parent::__construct($model);
    }

    /**
     * Get preferences object appropriate to user identifier.
     *
     * @param $userIdentifier
     * @return mixed
     */
    public function findByUserIdentifier($userIdentifier)
    {
        return $this->model->where('user_identifier', '=', $userIdentifier)->first();
    }

}