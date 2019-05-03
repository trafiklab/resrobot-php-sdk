<?php


namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Model\Contract\RoutePlanningResponse;

class ResRobotRoutePlanningResponse implements RoutePlanningResponse
{
    private $_trips;

    /**
     *
     * @param array $json
     */
    public function __construct(array $json)
    {
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
     * @param array $json The API output to parse.
     */
    private function parseApiResponse(array $json): void
    {
         foreach ($json['Trip'] as $key => $entry) {
            $this->_trips[] = new ResRobotTrip($entry);
        }
    }

}