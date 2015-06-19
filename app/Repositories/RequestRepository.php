<?php namespace Restboat\Repositories;

use Restboat\Models\Request;

class RequestRepository extends BaseRepository {

    /**
     * @param Request $model
     */
    function __construct(Request $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $userId
     * @param $path
     * @return \Restboat\Models\Request
     */
    public function findOrCreate($userId, $path)
    {
        return $this->model->firstOrCreate(
            array(
                'user_id'   => $userId,
                'path'      => $path,
            )
        );
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findWithLogs($id)
    {
        return $this->model
            ->where('id', '=', $id)
            ->with('requestLogs')
            ->first();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getByUser($userId)
    {
        // TODO - make order by with recent log created_at
        return $this->model
            ->orderBy('created_at', 'desc')
            ->where('user_id', '=', $userId)
            ->with('requestLogs')
            ->paginate(7);
    }

    /**
     * @param $userId
     * @param $search
     * @return mixed
     */
    public function searchByUser($userId, $search)
    {
        // TODO - make order by with recent log created_at
        return $this->model
            ->orderBy('created_at', 'desc')
            ->where('user_id', '=', $userId)
            ->where('path', 'like', '%' . $search . '%')
            ->with('requestLogs')
            ->paginate(7);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function deleteByUser($userId)
    {
        return $this->model
            ->where('user_id', '=', $userId)
            ->delete();
    }

}