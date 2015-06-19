<?php  namespace Restboat\Observers; 

use Restboat\Services\RequestLimiterService;

class RequestLogObserver {

    /**
     * @var \Restboat\Services\RequestLimiterService
     */
    private $service;

    /**
     * @param \Restboat\Services\RequestLimiterService $service
     */
    function __construct(RequestLimiterService $service)
    {
        $this->service = $service;
    }

    /**
     * Called when a Request model is saved.
     *
     * @param $model
     */
    public function saved($model)
    {
        $this->service->limitRequestLog($model);
    }

}