<?php namespace Restboat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Restboat\Http\Requests\HistoryRequest;
use Restboat\Services\CurlService;
use Restboat\Services\HistoryService;

class HistoryController extends Controller {

    /**
     * @var \Restboat\Services\HistoryService
     */
    private $service;

    /**
     * @param \Restboat\Services\HistoryService $service
     */
    function __construct(HistoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Session\Store $session
     * @return \Restboat\Http\Controllers\Response
     */
	public function index(Request $request, Store $session)
	{
        $search = $request->get('search');

		return $search ? $this->service->searchRequestLogs($search) : $this->service->listRequestLogs();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(HistoryRequest $request)
	{
		return $this->service->sendRequest($request);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return $this->service->showRequestLog($id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id = null)
	{
        return $this->service->remove($id);
	}

}
