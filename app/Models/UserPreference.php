<?php namespace Restboat\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserPreference extends BaseModel {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user_preferences';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array(
        'user_id', 'user_identifier', 'timezone', 'request_limit', 'request_log_limit',
        'default_response_status', 'default_response_type', 'default_response_content',
        'list_unique', 'sort_by_time', 'boat_token_enable', 'boat_token'
    );

    /**
     * Get user of this preferences.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Restboat\Models\User', 'user_id', 'id');
    }

}
