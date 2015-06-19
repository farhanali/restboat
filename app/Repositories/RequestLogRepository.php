<?php namespace Restboat\Repositories;

use Restboat\Models\RequestLog;

class RequestLogRepository extends BaseRepository {

    /**
     * @param RequestLog $model
     */
    function __construct(RequestLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getByUser($userId)
    {
        return $this->model
            ->whereIn('request_id', function($query) use ($userId)
            {
                $query->from('requests')
                    ->where('user_id', '=', $userId)
                    ->lists('id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(7);
    }

    /**
     * @param $userId
     * @param $search
     * @return mixed
     */
    public function searchByUser($userId, $search)
    {
        return $this->model
            ->whereIn('request_id', function($query) use ($userId, $search)
            {
                $query->from('requests')
                    ->where('user_id', '=', $userId)
                    ->where('path', 'like', '%' . $search . '%')
                    ->lists('id');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(7);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function deleteByUser($userId)
    {
        return $this->model
            ->join('requests', 'request_logs.request_id', '=', 'requests.id')
            ->where('requests.user_id', '=', $userId)
            ->delete();
    }

}
