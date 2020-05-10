<?php
namespace Forshot\Curl;

use Forshot\Request;

class Curl
{
    /** @var resource */
    private $curl;

    /** @var array */
    private $info = [];

    /** @var array */
    private $error;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->curl = curl_init($url);
    }

    /**
     * Set the curl options
     * 
     * @param array $options
     */
    public function setOpt(array $options)
    {
        curl_setopt_array($this->curl, $options);
    }

    /**
     * Execute Curl
     */
    public function execute()
    {
        $result = curl_exec($this->curl);
        $this->info = curl_getinfo($this->curl);
        if (curl_errno($this->curl) > 0) {
            $this->error = [
                'no' => curl_errno($this->curl),
                'message' => curl_error($this->curl),
            ];
        }
        return $result;
    }

    /**
     * Return the error number
     * 
     * @return array Return error information
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return boolean 
     */
    public function hasError()
    {
        return !empty($this->error);
    }

    /**
     * Return the value based on the key
     * 
     * @return mixed
     */
    public function info($key)
    {
        return isset($this->info[$key]) ? $this->info[$key] : null;
    }

    /**
     * Close the curl
     */
    public function __destruct()
    {
        curl_close($this->curl);
    }
}