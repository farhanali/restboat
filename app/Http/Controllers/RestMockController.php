<?php namespace Restboat\Http\Controllers;

use Illuminate\Http\Request;
use Restboat\Services\RestMockService;

class RestMockController extends Controller {

    /**
     * @param \Illuminate\Http\Request $request
     * @param \Restboat\Services\RestMockService $service
     * @param $userIdentifier
     * @param null $path
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
	public function mockRequest(Request $request, RestMockService $service, $userIdentifier, $path = null)
	{
        return $service->mockRequest($request, $userIdentifier, $path);
	}

}
