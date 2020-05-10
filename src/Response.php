<?php
namespace Forshot;

interface Response
{
    /**
     * Return Success or not
     * 
     * @return boolean 
     */
    public function isSucceeded();

    /**
     * Return the HTTP status code
     * 
     * @return integer
     */
    public function status();

    /**
     * Return a header item by the key
     * 
     * @param string $key
     * @return mixed $value|null
     */
    public function header($key);

    /**
     * Return the formatted data from the response
     * 
     * @return array|string 
     */
    public function getBody();

    /**
     * Return the raw response body
     * 
     * @return string Raw body from the response
     */
    public function getRawBody();
}