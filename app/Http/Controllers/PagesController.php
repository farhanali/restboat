<?php namespace Restboat\Http\Controllers;

use Restboat\Services\PagesService;

class PagesController extends Controller {

    /**
     * @param \Restboat\Services\PagesService $service
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(PagesService $service)
    {
        return $service->getIndex();
    }

    /**
     * Get the user tips page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tips()
    {
        return response()->view('tips');
    }

}
