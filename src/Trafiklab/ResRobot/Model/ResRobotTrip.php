<?php


namespace Trafiklab\ResRobot\Model;

use Trafiklab\Common\Model\Contract\RoutePlanningLeg;
use Trafiklab\Common\Model\Contract\Trip;
use Trafiklab\Common\Model\Contract\VehicleStop;

/**
 * A Trip, often also called Journey, describes one possibility for travelling between two locations. A Trip can
 * consist of one or more legs. A leg is one part of a Trip, made with a single vehicle or on foot. In the case of
 * multiple legs, a transfer is required between two legs.
 *
 * @package Trafiklab\ResRobot\Model
 */
class ResRobotTrip implements Trip
{

    private $_legs;

    /**
     * ResRobotTrip constructor.
     *
     * @param array $json
     *
     * @internal
     */
    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * A leg is one part of a Trip, made with a single vehicle or on foot. A Trip can consist of one or more
     * legs. In the case of multiple legs, a transfer is required between two legs.
     *
     * @return RoutePlanningLeg[] An array containing the legs in this Trip.
     */
    public function getLegs(): array
    {
        return $this->_legs;
    }

    /**
     * Get the duration of this trip in seconds.
     *
     * @return int
     */
    public function getDuration(): int
    {
        return $this->getArrival()->getScheduledArrivalTime()->getTimestamp() -
            $this->getDeparture()->getScheduledDepartureTime()->getTimestamp();
    }

    /**
     * Get the departure for the first leg.
     *
     * @return VehicleStop
     */
    public function getDeparture(): VehicleStop
    {
        if (count($this->_legs) < 1) {
            return null;
        }
        return $this->_legs[0]->getDeparture();
    }

    /**
     * Get the arrival for the last leg.
     *
     * @return VehicleStop
     */
    public function getArrival(): VehicleStop
    {
        if (count($this->_legs) < 1) {
            return null;
        }
        return end($this->_legs)->getArrival();
    }

    /**
     * Parse (a part of) an API response and store the result in this object.
     *
     * @param array $json
     */
    private function parseApiResponse(array $json)
    {
        $this->_legs = [];
        foreach ($json['LegList']['Leg'] as $leg) {
            $this->_legs[] = new ResRobotLeg($leg);
        }
    }
}