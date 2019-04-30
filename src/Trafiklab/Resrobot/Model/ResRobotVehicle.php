<?php


namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Model\Contract\Vehicle;

class ResRobotVehicle implements Vehicle
{

    private $_name;
    private $_number;
    private $_type;
    private $_operatorCode;
    private $_operatorName;
    private $_operatorUrl;

    /**
     * Product constructor.
     *
     * @param array $json
     */
    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * The number of the vehicle, uniquely identifying the trip it makes on a given day. Example: 547.
     * @return int
     */
    public function getNumber(): int
    {
        return $this->_number;
    }

    /**
     * The type of vehicle. Example: "Snabbtåg".
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * The code for the operator who runs the vehicle. Example: 74.
     * @return int
     */
    public function getOperatorCode(): int
    {
        return $this->_operatorCode;
    }

    /**
     * The URL for the operator whi runs the vehicle. Example: "http://www.sj.se"
     * @return string
     */
    public function getOperatorUrl()
    {
        return $this->_operatorUrl;
    }

    /**
     * The name for the operator whi runs the vehicle. Example: "SJ"
     * @return string
     */
    public function getOperatorName(): string
    {
        return $this->_operatorName;
    }

    private function parseApiResponse(array $json)
    {
        $this->_name = $json['name'];
        $this->_number = $json['num'];
        $this->_type = $json['catOutL'];
        $this->_operatorCode = $json['operatorCode'];
        $this->_operatorName = $json['operator'];
        $this->_operatorUrl = $json['operatorUrl'];
    }
}
