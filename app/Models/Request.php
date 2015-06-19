<?php namespace Restboat\Models;

use McCool\LaravelAutoPresenter\HasPresenter;

class Request extends BaseModel implements HasPresenter {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('user_id', 'path');

    /**
     * Get the user own this request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('Restboat\Models\User', 'user_id', 'id');
    }

    /**
     * Get the logs for a request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestLogs()
    {
        return $this->hasMany('Restboat\Models\RequestLog', 'request_id', 'id');
    }

    /**
     * Get the response assigned to a request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function response()
    {
        return $this->hasOne('Restboat\Models\Response', 'request_id', 'id');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'Restboat\Presenters\RequestPresenter';
    }

}
