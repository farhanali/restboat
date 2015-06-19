<?php  namespace Restboat\Services; 

use Restboat\Models\Request;
use Restboat\Models\RequestLog;

class RequestLimiterService {

    /**
     * Delete first request of the user if request_limit exceeds.
     *
     * @param \Restboat\Models\Request $model
     */
    public function limitRequest(Request $model)
    {
        $user           = $model->user;
        $preferences    = $user->preferences;

        $query = $model->where('user_id', '=', $user->id);

        if ($query->count() >  $preferences->request_limit)
        {
            $query->orderBy('id')->first()->delete();
        }
    }

    /**
     * Delete first log of the user if request_log_limit exceeds.
     *
     * @param \Restboat\Models\RequestLog $model
     */
    public function limitRequestLog(RequestLog $model)
    {
        $user           = $model->request->user;
        $preferences    = $user->preferences;

        $query = $model->whereIn('request_id', function($query) use ($user) {
            $query->from('requests')
                ->where('user_id', '=', $user->id)
                ->lists('id');
        });

        if ($query->count() >  $preferences->request_log_limit)
        {
            $query->orderBy('id')->first()->delete();
        }
    }

}