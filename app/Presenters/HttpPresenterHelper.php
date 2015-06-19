<?php  namespace Restboat\Presenters; 

use Restboat\Exceptions\MalFormattedContentException;

class HttpPresenterHelper {

    /**
     * Get http method as html span.
     *
     * @param $method
     * @return string
     */
    public static function getMethodSpan($method)
    {
        $colorMap = array(
            'GET'     => 'success',
            'POST'    => 'primary',
            'PUT'     => 'warning',
            'PATCH'   => 'warning',
            'DELETE'  => 'danger',
            'OPTIONS' => 'info',
        );

        $method = strtoupper($method);
        $color  = isset($colorMap[$method]) ? $colorMap[$method] : 'default';

        return '<span class="label label-' . $color . '">' . $method . '</span>';
    }

    /**
     * Get http content type as a html span.
     *
     * @param $contentType
     * @return string
     */
    public static function getContentTypeSpan($contentType)
    {
        $parts       = explode(';', $contentType);
        $contentType = $parts[0];

        switch ($contentType)
        {
            case '':
                return '';

            case 'unknown':
                $type  = 'unknown';
                $color = 'danger';
                break;

            case 'application/x-www-form-urlencoded':
                $type  = 'x-www-form-urlencoded';
                $color = 'info';
                break;

            case 'application/json':
                $type  = 'JSON';
                $color = 'success';
                break;

            case 'application/xml':
                $type  = 'XML';
                $color = 'primary';
                break;

            default:
                $type  = $contentType;
                $color = 'warning';
                break;
        }

        return '<span class="label label-' . $color . '">' . $type . '</span>';
    }

    /**
     * Get the request content prettified corresponding to the content type.
     *
     * @param $contentType
     * @param $content
     * @return string
     * @throws \Restboat\Exceptions\MalFormattedContentException
     */
    public static function getPrettyContent($contentType, $content)
    {
        $parts       = explode(';', $contentType);
        $contentType = $parts[0];

        switch ($contentType)
        {
            case '':
                return '';

            case 'application/x-www-form-urlencoded':
                $array = array();
                parse_str($content, $array);
                return HttpPresenterHelper::arrayToTable($array);

            case 'application/json':
                $array     = json_decode($content, true);
                $formatted = json_encode($array, JSON_PRETTY_PRINT);

                if($formatted == 'null' || empty($formatted))
                {
                    throw new MalFormattedContentException("Json content is not formatted properly");
                }

                return '<pre><code class="json">' . e($formatted) . '</code></pre>';

            case 'application/xml':
                $dom = new \DOMDocument();
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($content);
                $dom->formatOutput = TRUE;
                
                return '<pre><code class="xml">' . e($dom->saveXml()) . '</code></pre>';

            case 'multipart/form-data':
                $array     = json_decode($content, true);

                return HttpPresenterHelper::arrayToTable($array);

            default:
                return '<pre><code>' . e($content) . '</code></pre>';
        }
    }

    /**
     * Get content type that have content to be prettified.
     *
     * @param $contentType
     * @return null|string
     */
    public static function getPrettyType($contentType)
    {
        $parts       = explode(';', $contentType);
        $contentType = $parts[0];

        switch ($contentType)
        {
            case '':
                return null;

            case 'application/x-www-form-urlencoded':
                return 'x-www-form-urlencoded';

            case 'application/json':
                return 'JSON';

            case 'application/xml':
                return 'XML';

            case 'multipart/form-data':
                return 'Form-Data';

            default:
                return $contentType;
        }
    }

    /**
     * Convert an array to a html table.
     *
     * @param $array
     * @return string
     */
    public static function arrayToTable($array)
    {
        if ( ! is_array($array))
            return $array;

        $table = '';

        foreach ($array as $key => $value)
        {
            $table .= '<tr>';
            $table .= '<th>' . $key . '</th>';
            $table .= '<td>' . self::arrayToTable($value) . '</td>';
        }

        $table = '<table class="table table-bordered" width="100%">' . $table . '</table>';

        return $table;
    }

}