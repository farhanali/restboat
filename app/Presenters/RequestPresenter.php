<?php  namespace Restboat\Presenters; 

use McCool\LaravelAutoPresenter\BasePresenter;
use Restboat\Models\Request;

class RequestPresenter extends BasePresenter {

    public function __construct(Request $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Get the recent log of a request.
     *
     * @return mixed
     */
    public function recentLog()
    {
        return $this->wrappedObject->requestLogs->last();
    }

}