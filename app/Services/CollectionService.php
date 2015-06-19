<?php  namespace Restboat\Services; 

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\Store;
use Restboat\Http\Requests\CollectionRequest;
use Restboat\Models\Request;
use Restboat\Models\Response;
use Restboat\Repositories\RequestLogRepository;
use Restboat\Repositories\RequestRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class CollectionService {

    /**
     * @var \Illuminate\Auth\Guard
     */
    private $auth;

    /**
     * @var \Illuminate\Session\Store
     */
    private $session;

    /**
     * @var \Restboat\Repositories\RequestRepository
     */
    private $requestRepo;

    /**
     * @var \Restboat\Repositories\RequestLogRepository
     */
    private $requestLogRepo;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @param \Illuminate\Session\Store $session
     * @param \Restboat\Repositories\RequestRepository $requestRepo
     * @param \Restboat\Repositories\RequestLogRepository $requestLogRepo
     */
    function __construct(Guard $auth, Store $session, RequestRepository $requestRepo, RequestLogRepository $requestLogRepo)
    {
        $this->auth             = $auth;
        $this->session          = $session;
        $this->requestRepo      = $requestRepo;
        $this->requestLogRepo   = $requestLogRepo;
    }

    /**
     * Get all requests of logged user.
     *
     * @return \Illuminate\View\View
     */
    public function listRequests()
    {
        $this->session->remove('search');

        $requests = $this->requestRepo->getByUser($this->auth->id());

        if ($requests->count() > 0)
        {
            $selectedRequest    = $requests->first();
            $response           = $this->response($selectedRequest);

            return view('user.collections.index', compact('requests', 'selectedRequest', 'response'));
        }

        return view('user.collections.empty');
    }

    /**
     * Search requests with a phrase, within the requests of logged user.
     *
     * @param $search
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function searchRequests($search)
    {
        $requests = $this->requestRepo->searchByUser($this->auth->id(), $search);

        if ($requests->count() > 0)
        {
            $this->session->set('search', $search);

            $selectedRequest    = $requests->first();
            $response           = $this->response($selectedRequest);

            return view('user.collections.index', compact('requests', 'selectedRequest', 'response'));
        }

        flash()->info('No request found for search : ' . $search);

        return redirect()->route('collections.index');
    }

    /**
     * Add a request format to the collection.
     *
     * @param \Restboat\Http\Requests\CollectionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addRequest(CollectionRequest $request)
    {
        $this->requestRepo->findOrCreate($this->auth->id(), $request->get('path'));

        flash()->success('New request created successfully !');

        return redirect()->route('collections.index');
    }

    /**
     * Show a selected request from a list.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showRequest($id)
    {
        $this->abortIfNotAuthorized($id);

        $search = $this->session->get('search');
        $userId = $this->auth->id();

        // check selection is done from a search result, if so retrieve search result or retrieve all.
        $requests = $search
            ? $this->requestRepo->searchByUser($userId, $search)
            : $this->requestRepo->getByUser($userId);

        $selectedRequest    = $this->requestRepo->findWithLogs($id);
        $response           = $this->response($selectedRequest);

        return view('user.collections.index', compact('requests', 'selectedRequest', 'response'));
    }

    /**
     * Remove either a single request or remove all requests of logged user if $id is null.
     *
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id = null)
    {
        // remove single request with id.
        if ($id)
        {
            $this->abortIfNotAuthorized($id);

            $this->requestRepo->delete($id);

            flash()->success('Request removed from collections !');
        }
        // remove all requests of the logged user.
        else
        {
            $this->requestRepo->deleteByUser($this->auth->id());

            flash()->success('All requests and logs removed from collections !');
        }

        return redirect()->route('collections.index');
    }

    /**
     * Get response of a request if set, or default response.
     *
     * @param \Restboat\Models\Request $request
     * @return mixed
     */
    private function response(Request $request)
    {
        return $request->response ?: $this->defaultResponse();
    }

    /**
     * Get default response of logged user.
     *
     * @return \Restboat\Models\Response
     */
    private function defaultResponse()
    {
        $user = $this->auth->user();

        $response = new Response();

        $response->content      = $user->preferences->default_response_content;
        $response->status       = $user->preferences->default_response_status;
        $response->content_type = $user->preferences->default_response_type;

        return $response;
    }

    /**
     * Abort the request as forbidden if user is not authorized to access requested resource.
     *
     * @param $requestId
     */
    private function abortIfNotAuthorized($requestId)
    {
        try
        {
            $request = $this->requestRepo->find($requestId);

            if ( ! $this->isUserAuthorized($request))
            {
                abort(403);
            }
        }
        catch(ModelNotFoundException $ex)
        {
            abort(404);
        }
    }

    /**
     * Checks whether the passed request is owned by logged user.
     *
     * @param \Restboat\Models\Request $request
     * @return bool
     */
    private function isUserAuthorized(Request $request)
    {
        return $request->user->id == $this->auth->id();
    }

}