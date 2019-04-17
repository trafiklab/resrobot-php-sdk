<?php


namespace Trafiklab\Resrobot\Internal;


interface WebClient
{
    /**
     * @param string $endpoint
     * @param array  $parameters
     *
     * @return mixed
     */
    function makeRequest(string $endpoint, array $parameters);
}