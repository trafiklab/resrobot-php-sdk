<?php

namespace Trafiklab\Resrobot\Internal;

class CurlWebClient implements WebClient
{

    private const CACHE_TTL = 15;
    private $_userAgent;
    private $_cache; // Cache validity in seconds

    public function __construct($userAgent)
    {
        $this->_userAgent = $userAgent;
        $this->_cache = new ResRobotCache();
    }

    function makeRequest(string $endpoint, array $parameters): WebResponse
    {
        // url-encode parameters
        array_walk($parameters, function (&$value, $key) {
            $value = $key . '=' . urlencode($value);
        });

        // Create the URL
        $url = $endpoint . '?' . join('&', $parameters);
        if ($this->_cache->contains($url)) {
            return $this->_cache->get($url);
        }

        // create curl resource
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_USERAGENT, $this->_userAgent);
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds

        // $output contains the output string
        $output = curl_exec($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = new WebResponse($httpCode, $output);

        // close curl resource to free up system resources
        curl_close($ch);

        $this->_cache->put($url, $response, self::CACHE_TTL);

        return $response;
    }

}