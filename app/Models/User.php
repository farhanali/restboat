<?php namespace Restboat\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('name', 'email', 'avatar', 'provider', 'provider_id');

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('remember_token');

    /**
     * Get requests of a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests()
    {
        return $this->hasMany('Restboat\Models\Request', 'user_id', 'id');
    }

    /**
     * Get requests logged by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function requestLogs()
    {
        return $this->hasManyThrough(
            'Restboat\Models\RequestLog', 'Restboat\Models\Request',
            'user_id', 'request_id'
        );
    }

    /**
     * Get responses set for the user's requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function responses()
    {
        return $this->hasManyThrough(
            'Restboat\Models\Response', 'Restboat\Models\Request',
            'user_id', 'request_id'
        );
    }

    /**
     * Get preferences of a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function preferences()
    {
        return $this->hasOne('Restboat\Models\UserPreference', 'user_id', 'id');
    }

}
