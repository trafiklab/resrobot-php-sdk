<?php

namespace Trafiklab\Resrobot\Internal;

class CurlWebClient implements WebClient
{

    private $_userAgent;
    private $_cache;

    public function __construct($userAgent)
    {
        $this->_userAgent = $userAgent;
        $this->_cache = new ResRobotCache();
    }

    function makeRequest(string $endpoint, array $parameters)
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

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);

        // TODO: get TTL from HTTP headers
        $this->_cache->put($url, $output, 15);

        return $output;
    }

}