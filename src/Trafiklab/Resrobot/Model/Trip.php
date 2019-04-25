<?php


namespace Trafiklab\ResRobot\Model;

/**
 * A Trip, often also called Journey, describes one possibility for travelling between two locations. A Trip can
 * consist of one or more legs. A leg is one part of a Trip, made with a single vehicle or on foot. In the case of
 * multiple legs, a transfer is required between two legs.
 * @package Trafiklab\ResRobot\Model
 */
class Trip
{

    private $_legs;

    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * A leg is one part of a Trip, made with a single vehicle or on foot. A Trip can consist of one or more
     * legs. In the case of multiple legs, a transfer is required between two legs.
     *
     * @return Leg[] An array containing the legs in this Trip.
     */
    public function getLegs(): array
    {
        return $this->_legs;
    }

    private function parseApiResponse(array $json)
    {
        $this->_legs = [];
        foreach ($json['LegList']['Leg'] as $leg) {
            $this->_legs[] = new Leg($leg);
        }
    }
}