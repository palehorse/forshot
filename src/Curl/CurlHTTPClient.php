<?php
namespace Forshot\Curl;

use Forshot\HTTP\HTTPRequest;
use Forshot\HTTP\HTTPResponse;
use Forshot\HTTPClient;
use RuntimeException;

class CurlHTTPClient implements HTTPClient
{

    /**
     * Send a GET request
     * 
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function get($url, array $data = [], array $headers = [])
    {
        if (!empty($data)) {
            $url .= '?' . http_build_query($data);
        }
        
        return $this->sendRequest($url, 'GET', [], $headers);
    }

    /**
     * Send a POST request
     * 
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return Response
     */
    public function post($url, array $data = [], array $headers = [])
    {
        return $this->sendRequest($url, 'POST', $data, $headers);
    }
    
    /**
     * Send a DELETE request
     * 
     * @param string $url
     * @return Response
     */
    public function delete($url)
    {
        return $this->sendRequest($url, 'DELETE', [], []);
    }
    /**
     * Send a HTTP request by Curl
     * 
     * @param string $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return Response
     */
    private function sendRequest($url, $method, array $data = [], array $headers = [])
    {   
        $curl = new Curl($url);
        $options = $this->getCurlOptions($method, $headers, $data);
        $curl->setOpt($options);
        $result = $curl->execute();

        if ($curl->hasError()) {
            throw new RuntimeException('Send request error: ' . json_encode($curl->error()));
        }

        $httpStatusCode = $curl->info('http_code');
        $headerString = substr($result, 0, $curl->info('header_size'));
        $bodyString = substr($result, $curl->info('header_size'));

        $headers = [];
        
        foreach (explode("\r\n", $headerString) as $header) {
            $items = explode(':', $header, 2);
            if (count($items) == 2) {
                list($key, $value) = $items;
                $headers[$key] = trim($value);
            }
        }

        return new HTTPResponse($httpStatusCode, $headers, $bodyString);
    }

    /**
     * Return curl options
     * 
     * @param string $method
     * @param array $headers
     * @param mixed $data
     * @return array The curl options
     */
    private function getCurlOptions($method, array $headers = [], $data=null)
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_HEADER => true,
        ];

        if (strtoupper($method) == 'POST') {
            if (!empty($data['__file'] && !empty($data['__type']))) {
                $options[CURLOPT_PUT] = true;
                $options[CURLOPT_INFILE] = fopen($data['__file'], 'r');
                $options[CURLOPT_INFILESIZE] = filesize($data['__file']);
            } elseif (in_array('Content-Type: application/x-www-form-urlencoded', $headers)) {
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = http_build_query($data);
            } else if (in_array('Content-Type: application/json', $headers)) {
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
            }
        }   

        return $options;
    }
}