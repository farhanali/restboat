<?php namespace Restboat\Http\Controllers;

use Illuminate\Http\Request;
use Restboat\Http\Requests\UserPreferenceRequest;
use Restboat\Services\UserPreferenceService;

class UserPreferenceController extends Controller {

    /**
     * @var \Restboat\Services\UserPreferenceService
     */
    private $service;

    /**
     * @param \Restboat\Services\UserPreferenceService $service
     */
    public function __construct(UserPreferenceService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
	public function show()
	{
        return $this->service->show();
	}

    /**
     * @param \Restboat\Http\Requests\UserPreferenceRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
	public function update(UserPreferenceRequest $request)
	{
        return $this->service->update($request);
	}

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function updateToken()
    {
        return $this->service->updateToken();
    }

}
