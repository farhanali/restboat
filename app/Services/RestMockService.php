<?php  namespace Restboat\Services; 

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Restboat\Models\Response;
use Restboat\Models\User;
use Restboat\Repositories\RequestLogRepository;
use Restboat\Repositories\RequestRepository;
use Restboat\Repositories\ResponseRepository;
use Restboat\Repositories\UserPreferenceRepository;
use Restboat\Repositories\UserRepository;

class RestMockService {

    /**
     * @var \Illuminate\Auth\Guard
     */
    private $auth;

    /**
     * @var \Restboat\Repositories\RequestRepository
     */
    private $requestRepo;

    /**
     * @var \Restboat\Repositories\RequestLogRepository
     */
    private $logRepo;

    /**
     * @var \Restboat\Repositories\ResponseRepository
     */
    private $responseRepo;

    /**
     * @var \Restboat\Repositories\UserRepository
     */
    private $userRepo;

    /**
     * @var \Restboat\Repositories\UserPreferenceRepository
     */
    private $preferenceRepo;

    /**
     * @param \Illuminate\Auth\Guard $auth
     * @param \Restboat\Repositories\RequestRepository $requestRepo
     * @param \Restboat\Repositories\RequestLogRepository $logRepo
     * @param \Restboat\Repositories\ResponseRepository $responseRepo
     * @param \Restboat\Repositories\UserRepository $userRepo
     * @param \Restboat\Repositories\UserPreferenceRepository $preferenceRepo
     */
    public function __construct(
        Guard $auth, RequestRepository $requestRepo,
        RequestLogRepository $logRepo, ResponseRepository $responseRepo,
        UserRepository $userRepo, UserPreferenceRepository $preferenceRepo)
    {
        $this->auth             = $auth;
        $this->requestRepo      = $requestRepo;
        $this->logRepo          = $logRepo;
        $this->responseRepo     = $responseRepo;
        $this->userRepo         = $userRepo;
        $this->preferenceRepo   = $preferenceRepo;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $userIdentifier
     * @param $path
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function mockRequest(Request $request, $userIdentifier, $path)
    {
        $userPreference = $this->preferenceRepo->findByUserIdentifier($userIdentifier);

        if ( ! $this->authorizeRequest($request, $userPreference)) return $this->unauthorizedError();

        $user = $userPreference->user;

        if (empty($path)) return $this->getDefaultResponse($user);

        $restRequest    = $this->saveRequest($user->id, $path);
        $requestLog     = $this->saveRequestLog($restRequest->id, $request);

        return $this->getResponse($restRequest, $user);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param $userPreference
     * @return bool
     */
    private function authorizeRequest(Request $request, $userPreference)
    {
        if (empty($userPreference))
        {
            return false;
        }

        if ($userPreference->boat_token_enable)
        {
            return $userPreference->boat_token == $this->getAuthTokenFromHeader();
        }

        return true;
    }

    /**
     * Get the authorization token from header.
     *
     * @return null|string
     */
    private function getAuthTokenFromHeader()
    {
        $token = null;

        $headers = apache_request_headers();
        if(isset($headers['Authorization']))
        {
            $matches = array();
            preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);

            if(isset($matches[1]))
            {
                $token = $matches[1];
            }
        }

        return $token;
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
     * @param \Illuminate\Http\Request $request
     * @return \Restboat\Models\BaseModel
     */
    private function saveRequestLog($requestId, Request $request)
    {
        return $this->logRepo->create(
            array(
                'request_id'    => $requestId,
                'method'        => $request->method(),
                'query_string'  => $request->getQueryString(),
                'headers'       => json_encode(getallheaders()),
                'content_type'  => $this->getContentType($request->method()),
                'content'       => $this->getContent($request),
            )
        );
    }

    /**
     * @param \Restboat\Models\Request $request
     * @param \Restboat\Models\User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    private function getResponse(\Restboat\Models\Request $request, User $user)
    {
        // if request has set a response, return it
        if ($request->response)
        {
            return $this->makeResponse(
                $request->response->content,
                $request->response->status,
                $request->response->content_type
            );
        }

        // return default response
        return $this->getDefaultResponse($user);
    }

    /**
     * @param \Restboat\Models\User $user
     * @return \Restboat\Models\Response
     */
    private function getDefaultResponse(User $user)
    {
        return $this->makeResponse(
            $user->preferences->default_response_content,
            $user->preferences->default_response_status,
            $user->preferences->default_response_type
        );
    }

    /**
     * @param $content
     * @param $status
     * @param $contentType
     * @return \Restboat\Models\Response
     */
    private function makeResponse($content, $status, $contentType)
    {
        return response($content, $status, array('Content-Type' => $contentType));
    }

    /**
     * Return an unauthorized error response.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function unauthorizedError()
    {
        $data = array(
            'error_code'    => 401,
            'error_message' => 'Unauthorized',
        );

        return response()->json($data, 401);
    }

    /**
     * @param $method
     * @return string|null
     */
    private function getContentType($method)
    {
        if (isset($_SERVER['CONTENT_TYPE']))
            return $_SERVER['CONTENT_TYPE'];

        if (isset($_SERVER['HTTP_CONTENT_TYPE']))
            return $_SERVER['HTTP_CONTENT_TYPE'];

        return null;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getContent(Request $request)
    {
        return $request->getContent() ?: $this->getFormData($request);
    }

    /**
     * Get form data with file information if any as json.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    private function getFormData(Request $request)
    {
        $arrayObject    = new \ArrayObject($_POST);
        $postData      = $arrayObject->getArrayCopy();

        $files = $this->getFiles($request);

        $postData = $files ? array_merge($postData, $files) : $postData;

        return json_encode($postData);
    }

    /**
     * Get files info from request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function getFiles(Request $request)
    {
        $files = array();
        foreach ($request->files->all() as $name => $file)
        {
            $files[$name] = [
                'file_type' => $file->getClientMimeType(),
                'name' => $file->getClientOriginalName(),
                'size' => $file->getClientSize()
            ];
        }

        return $files;
    }

}