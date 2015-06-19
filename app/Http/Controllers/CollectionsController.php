<?php namespace Restboat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Restboat\Http\Requests\CollectionRequest;
use Restboat\Services\CollectionService;

class CollectionsController extends Controller {

    /**
     * @var \Restboat\Services\CollectionService $service
     */
    private $service;

    /**
     * @param \Restboat\Services\CollectionService $service
     */
    public function __construct(CollectionService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Session\Store $session
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
	public function index(Request $request, Store $session)
	{
        $search = $request->get('search');

		return $search ? $this->service->searchRequests($search) : $this->service->listRequests();
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param \Restboat\Http\Requests\CollectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(CollectionRequest $request)
	{
		return $this->service->addRequest($request);
	}

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
	public function show($id)
	{
        return $this->service->showRequest($id);
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
	public function destroy($id = null)
	{
        return $this->service->remove($id);
	}

}
