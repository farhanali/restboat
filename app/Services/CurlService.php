<?php  namespace Restboat\Services;

class CurlService {

    /**
     * Form and send a GET request to the specified url with query parameters.
     *
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function get($url, $params = array())
    {
        return $this->send($url, 'GET', null, null, $params);
    }

    /**
     * Form and send a POST request to the specified url with raw data and parameters.
     *
     * @param $url
     * @param $rawData
     * @param string $contentType
     * @param array $params
     * @return mixed
     */
    public function post($url, $rawData = null, $contentType = 'application/json', $params = array())
    {
        return $this->send($url, 'POST', $rawData, $contentType, $params);
    }

    /**
     * Form and send a PUT request to the specified url with raw data and parameters.
     *
     * @param $url
     * @param null $rawData
     * @param string $contentType
     * @param array $params
     * @return mixed
     */
    public function put($url, $rawData = null, $contentType = 'application/json', $params = array())
    {
        return $this->send($url, 'PUT', $rawData, $contentType, $params);
    }

    /**
     * Form and send a DELETE request to the specified url with raw data and parameters.
     *
     * @param $url
     * @param null $rawData
     * @param string $contentType
     * @param array $params
     * @return mixed
     */
    public function delete($url, $rawData = null, $contentType = 'application/json', $params = array())
    {
        return $this->send($url, 'DELETE', $rawData, $contentType, $params);
    }

    /**
     * Form and send a GET/POST/PUT/DELETE request to the specified url with raw data and parameters.
     *
     * @param $url
     * @param string $method
     * @param null $rawData
     * @param string $contentType
     * @param array $params
     * @return mixed
     */
    public function send($url, $method = 'GET', $rawData = null, $contentType = 'application/json', $params = array())
    {
        $curl = $this->init($url, $params);

        // opt out load data and content type for GET request
        if ($method != 'GET')
        {
            $curl = $this->load($curl, $method, $rawData, $contentType);
        }

        return $this->execute($curl);
    }

    /**
     * Init curl with url and parameters.
     *
     * @param $url
     * @param $params
     * @return resource
     */
    private function init($url, $params)
    {
        // encode parameters
        $paramString = '';
        if (is_array($params) && count($params) != 0 )
        {
            $paramString = '?'. http_build_query($params);
        }

        // set up the curl resource
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . $paramString);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return $curl;
    }

    /**
     * Setup method, content type and load data.
     *
     * @param $curl
     * @param $method
     * @param $rawData
     * @param $contentType
     * @return mixed
     */
    private function load($curl, $method, $rawData, $contentType)
    {
        // setup method
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        if (empty($rawData))
        {
            return $curl;
        }

        // setup data
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $rawData);

        // setup header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: ' . $contentType ,
            'Content-Length: ' . strlen($rawData)
        ));

        return $curl;
    }

    /**
     * Execute and close curl request.
     *
     * @param $curl
     * @return mixed
     */
    private function execute($curl)
    {
        // execute the request
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        curl_close($curl);

        return $output;
    }

}