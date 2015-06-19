<?php namespace Restboat\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model {

    /**
     * Magic method which is triggered by calling isset() or empty() on
     * inaccessible properties.
     *
     * @param string $name
     *
     * @return boolean
     */
    public function __isset($name)
    {
        if ($this->$name)
        {
            return true;
        }

        return false;
    }

    /**
     * Get mutator for created_at.
     *
     * @param $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        session('');

        $timezone = auth()->check()
            ? auth()->user()->preferences->timezone
            : config('app.timezone');

        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone($timezone);
    }

    /**
     * Get mutator for updated_at.
     *
     * @param $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        $timezone = auth()->check()
            ? auth()->user()->preferences->timezone
            : config('app.timezone');

        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone($timezone);
    }

}
