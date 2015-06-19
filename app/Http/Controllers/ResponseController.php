<?php namespace Restboat\Http\Controllers;

use Restboat\Http\Requests\ResponseRequest;
use Restboat\Services\ResponseService;

class ResponseController extends Controller {

    /**
     * Update the specified resource in storage.
     *
     * @param \Restboat\Http\Requests\ResponseRequest $request
     * @param \Restboat\Services\ResponseService $service
     * @return \Illuminate\Http\RedirectResponse
     */
	public function update(ResponseRequest $request, ResponseService $service)
	{
        return $service->saveResponse($request);
	}

}
