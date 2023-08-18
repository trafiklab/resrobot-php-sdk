<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

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

    /**
     * Parse (a part of) an API response and store the result in this object.
     *
     * @param array $json
     */
    private function parseApiResponse(array $json)
    {
        $this->_stopLocations = [];
        foreach ($json['stopLocationOrCoordLocation'] as $result) {
            if( ! isset($result['StopLocation'])) {
                continue;
            }
            $stopLocationJson = $result['StopLocation'];
            $this->_stopLocations[] = new ResRobotStopLocationLookupEntry($stopLocationJson);
        }
    }
}