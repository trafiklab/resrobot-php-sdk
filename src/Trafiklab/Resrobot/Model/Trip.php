<?php


namespace Trafiklab\ResRobot\Model;


class Trip
{

    private $_legs;

    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * @return Leg[]
     */
    public function getLegs() : array
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