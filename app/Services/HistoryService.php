<?php  namespace Restboat\Services; 

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\Store;
use Restboat\Http\Requests\HistoryRequest;
use Restboat\Models\Request;
use Restboat\Models\Response;
use Restboat\Repositories\RequestLogRepository;
use Restboat\Repositories\RequestRepository;

class HistoryService {

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
     * Get all request logs of logged user.
     *
     * @return \Illuminate\View\View
     */
    public function listRequestLogs()
    {
        $this->session->remove('search');

        $logs = $this->requestLogRepo->getByUser($this->auth->id());

        if ($logs->count() > 0)
        {
            $selectedLog   = $logs->first();
            $response   = $this->response($selectedLog->request);

            return view('user.history.index', compact('logs', 'selectedLog', 'response'));
        }

        return view('user.history.empty');
    }

    /**
     * Search request logs with a phrase, within the logs of logged user.
     *
     * @param $search
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function searchRequestLogs($search)
    {
        $logs = $this->requestLogRepo->searchByUser($this->auth->id(), $search);

        if ($logs->count() > 0)
        {
            $this->session->set('search', $search);

            $selectedLog   = $logs->first();
            $response   = $this->response($selectedLog->request);

            return view('user.history.index', compact('logs', 'selectedLog', 'response'));
        }

        flash()->info('No request logs found for search : ' . $search);

        return redirect()->route('history.index');
    }

    /**
     * Saves a request and a request log, done this way because of issue on making curl request to same server.
     * TODO - Send a request to the mock server specific to the logged user using curl.
     *
     * @param \Restboat\Http\Requests\HistoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendRequest(HistoryRequest $request)
    {
        // remove white spaces from input
        $request->merge(array_map('trim', $request->all()));

        // parse path to get path and query parameters separated
        $path           = parse_url($request->get('path'), PHP_URL_PATH);
        $params         = parse_url($path, PHP_URL_QUERY) ?: '';
        $method         = $request->get('method');
        $data           = $request->get('data');
        $contentType    = $data ? $request->get('content_type') : null;
        $headers        = json_encode($this->getHeaders());

        $mockRequest    = $this->saveRequest($this->auth->id(), $path);
        //dd($mockRequest->id, $method, $params, $contentType, $data, $headers);
        $mockLog        = $this->saveRequestLog($mockRequest->id, $method, $params, $contentType, $data, $headers);

        //dd($mockLog);

        flash()->success('Request made successfully !');

        return redirect()->route('history.index');
    }

    /**
     * Show a selected request log from a list.
     *
     * @param $id
     * @return \Illuminate\View\View
     */
    public function showRequestLog($id)
    {
        $this->abortIfNotAuthorized($id);

        $search = $this->session->get('search');
        $userId = $this->auth->id();

        // check selection is done from a search result, if so retrieve search result or retrieve all.
        $logs = $search
            ? $this->requestLogRepo->searchByUser($userId, $search)
            : $this->requestLogRepo->getByUser($userId);

        $selectedLog = $this->requestLogRepo->find($id);
        $response = $this->response($selectedLog->request);

        return view('user.history.index', compact('logs', 'selectedLog', 'response'));
    }

    /**
     * Remove either a single request log or remove all request logs of logged user if $id is null.
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

            $this->requestLogRepo->delete($id);

            flash()->success('Request log removed from history !');
        }
        // remove all requests of the logged user.
        else
        {
            $this->requestLogRepo->deleteByUser($this->auth->id());

            flash()->success('All requests logs removed from history !');
        }

        return redirect()->route('history.index');
    }

    /**
     * @param $userId
     * @param $path
     * @return \Restboat\Models\Request
     */
    private function saveRequest($userId, $path)
    {
        return $this->requestRepo->findOrCreate($userId, $path);
    }

    /**
     * @param $requestId
     * @param $method
     * @param $params
     * @param $contentType
     * @param $content
     * @param $headers
     * @return \Restboat\Models\BaseModel
     */
    private function saveRequestLog($requestId, $method, $params, $contentType, $content, $headers)
    {
        return $this->requestLogRepo->create(
            array(
                'request_id'    => $requestId,
                'method'        => $method,
                'query_string'  => $params,
                'headers'       => $headers,
                'content_type'  => $contentType,
                'content'       => $content,
            )
        );
    }

    /**
     * Get the request headers after setting Authorization header if boat_token_enable.
     *
     * @return mixed
     */
    private function getHeaders()
    {
        $headers = apache_request_headers();

        $userPreferences = $this->auth->user()->preferences;

        if ($userPreferences->boat_token_enable)
        {
            $headers['Authorization'] = 'Token token="' . $userPreferences->boat_token . '"';
        }

        return $headers;
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
     * @param $requestLogId
     */
    private function abortIfNotAuthorized($requestLogId)
    {
        try
        {
            $request = $this->requestLogRepo->find($requestLogId)->request;

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