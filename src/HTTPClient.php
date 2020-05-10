<?php
namespace Forshot;

interface HTTPClient
{
    /**
     * Send a GET request
     * 
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function get($url, array $data = [], array $headers = []);

    /**
     * Send a POST request
     * 
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function post($url, array $data = [], array $headers = []);

    /**
     * Send a DELETE request
     * 
     * @param string $url
     * @return Response
     */
    public function delete($url);
}