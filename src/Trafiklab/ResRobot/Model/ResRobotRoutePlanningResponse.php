<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Internal\WebResponseImpl;
use Trafiklab\Common\Model\Contract\RoutePlanningResponse;
use Trafiklab\Common\Model\Contract\WebResponse;

class ResRobotRoutePlanningResponse implements RoutePlanningResponse
{
    private $_trips;
    private $_response;

    /**
     *
     * @param WebResponse $response
     * @param array       $json
     *
     * @internal
     */
    public function __construct(WebResponse $response, array $json)
    {
        $this->_response = $response;
        $this->parseApiResponse($json);
    }

    /**
     * @return ResRobotTrip[]
     */
    public function getTrips(): array
    {
        return $this->_trips;
    }

    /**
     * Get the original response from the API.
     *
     * @return WebResponseImpl
     */
    public function getOriginalApiResponse(): WebResponse
    {
        return $this->_response;
    }

    /**
     * Parse (a part of) an API response and store the result in this object.
     *
     * @param array $json The API output to parse.
     */
    private function parseApiResponse(array $json): void
    {
        foreach ($json['Trip'] as $key => $entry) {
            $this->_trips[] = new ResRobotTrip($entry);
        }
    }
}