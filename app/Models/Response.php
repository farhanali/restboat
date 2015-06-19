<?php namespace Restboat\Models;

use McCool\LaravelAutoPresenter\HasPresenter;

class Response extends BaseModel implements HasPresenter {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('request_id', 'status', 'content_type', 'content');

    /**
     * Get request for this response.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function request()
    {
        return $this->belongsTo('Restboat\Models\Request', 'request_id', 'id');
    }

    /**
     * Get the presenter class.
     *
     * @return string
     */
    public function getPresenterClass()
    {
        return 'Restboat\Presenters\ResponsePresenter';
    }


}
