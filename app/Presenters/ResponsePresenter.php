<?php namespace Restboat\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Restboat\Exceptions\MalFormattedContentException;
use Restboat\Models\Response;

class ResponsePresenter extends BasePresenter {

    public function __construct(Response $resource)
    {
        parent::__construct($resource);
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

}
