<?php namespace Restboat\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Restboat\Exceptions\MalFormattedContentException;
use Restboat\Models\RequestLog;

class RequestLogPresenter extends BasePresenter {

    public function __construct(RequestLog $resource)
    {
        parent::__construct($resource);
    }

    /**
     * Get the created date as date time string.
     *
     * @return mixed
     */
    public function created()
    {
        return $this->wrappedObject->created_at->toDayDateTimeString();
    }

    /**
     * Get request method as a colored html span.
     *
     * @return string
     */
    public function requestMethod()
    {
        return HttpPresenterHelper::getMethodSpan($this->wrappedObject->method);
    }

    /**
     * Get request content type as a colored html span.
     *
     * @return string
     */
    public function contentType()
    {
        return HttpPresenterHelper::getContentTypeSpan($this->wrappedObject->content_type);
    }

    /**
     * Get the request content prettified corresponding to the content type.
     *
     * @return string
     */
    public function prettyContent()
    {
        try
        {
            return HttpPresenterHelper::getPrettyContent(
                $this->wrappedObject->content_type,
                $this->wrappedObject->content
            );
        }
        catch (MalFormattedContentException $ex)
        {
            return '<pre><code>' . 'Either content is empty or it is not formatted properly !' . '</code></pre>';
        }
    }

    /**
     * Get content type that have content to be prettified.
     *
     * @return string
     */
    public function prettyType()
    {
        return HttpPresenterHelper::getPrettyType($this->wrappedObject->content_type);
    }

    /**
     * Get query parameters as an html table.
     *
     * @return string
     */
    public function parametersTable()
    {
        $params = array();
        parse_str($this->wrappedObject->query_string, $params);

        return HttpPresenterHelper::arrayToTable($params);
    }

    /**
     * Get request headers as an html table.
     *
     * @return string
     */
    public function headersTable()
    {
        $headers = json_decode($this->wrappedObject->headers, true);

        return HttpPresenterHelper::arrayToTable($headers);
    }

}
