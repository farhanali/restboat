<?php namespace Restboat\Repositories;

use Restboat\Models\Response;

class ResponseRepository extends BaseRepository {

    /**
     * @param Response $model
     */
    function __construct(Response $model)
    {
        parent::__construct($model);
    }

}