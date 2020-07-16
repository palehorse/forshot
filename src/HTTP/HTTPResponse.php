<?php
namespace Forshot\HTTP;

use Forshot\Response;

class HTTPResponse implements Response
{
    /** @var integer */
    private $httpStatusCode;

    /** @var array */
    private $headers = [];

    /** @var string */
    private $body;

    /**
     * @param integer $httpStatusCode
     * @param array $responseHeaders
     * @param string $responseBody
     */
    public function __construct($httpStatusCode, $responseHeaders, $responseBody)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->headers = $responseHeaders;
        $this->body = $responseBody;
    }

    /**
     * Return success or not
     * 
     * @return boolean 
     */
    public function isSucceeded()
    {
        return $this->httpStatusCode == 200;
    }

    /**
     * Return HTTP Status Code
     * 
     * @return integer
     */
    public function status()
    {
        return $this->httpStatusCode;
    }

    /**
     * Return a header item by the key
     * 
     * @param string $key
     * @return mixed $value|null
     */
    public function header($key)
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * Return all headers
     * @return array
     */
    public function headers()
    {
        return $this->headers;
    }

    /**
     * Return the formatted data from the response
     * 
     * @return array|string 
     */
    public function getBody()
    {
        $data = json_decode($this->body, true);

        return !is_null($data) ? $data : $this->body;
    }

    /**
     * Return the raw response body
     * 
     * @return string Raw body from the response
     */
    public function getRawBody()
    {
        return $this->body;
    }
}