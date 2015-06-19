<?php  namespace Restboat\Services; 

use Restboat\Http\Requests\ResponseRequest;
use Restboat\Repositories\ResponseRepository;

class ResponseService {

    /**
     * @var \Restboat\Repositories\ResponseRepository
     */
    private $responseRepo;

    /**
     * @param \Restboat\Repositories\ResponseRepository $responseRepo
     */
    public function __construct(ResponseRepository $responseRepo)
    {
        $this->responseRepo = $responseRepo;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveResponse(ResponseRequest $request)
    {
        $values = $request->only('status', 'content_type', 'content');

        $matches['request_id'] = $request->get('request_id');

        $this->responseRepo->updateOrCreate($matches, $values);

        flash()->success('Response updated successfully !');

        return redirect()->back();
    }

}