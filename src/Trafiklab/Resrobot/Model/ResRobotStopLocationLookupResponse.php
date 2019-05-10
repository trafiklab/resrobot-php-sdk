<?php


namespace Trafiklab\Sl\Model;

use Trafiklab\Common\Model\Contract\StopLocationLookupResponse;
use Trafiklab\Common\Model\Contract\WebResponse;

class ResRobotStopLocationLookupResponse implements StopLocationLookupResponse
{
    private $originalResponse;
    private $_stopLocations;

    /**
     * ResRobotStopLocationLookupResponse constructor.
     *
     * @param WebResponse $response
     * @param mixed       $json
     */
    public function __construct(WebResponse $response, array $json)
    {
        $this->originalResponse = $response;
        $this->parseApiResponse($json);
    }

    /**
     * Get the original response from the API.
     *
     * @return WebResponse
     */
    public function getOriginalApiResponse(): WebResponse
    {
        return $this->originalResponse;
    }

    /**
     * An array containing the stop areas which were found.
     *
     * @return ResRobotStopLocationLookupEntry[]
     */
    public function getFoundStopLocations(): array
    {
        return $this->_stopLocations;
    }

    private function parseApiResponse(array $json)
    {
        $this->_stopLocations = [];
        foreach ($json['StopLocation'] as $stopLocationJson) {
            $this->_stopLocations[] = new ResRobotStopLocationLookupEntry($stopLocationJson);
        }
    }
}