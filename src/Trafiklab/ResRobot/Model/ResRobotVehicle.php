<?php


namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Model\Contract\Vehicle;
use Trafiklab\Common\Model\Enum\TransportType;

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
     *
     * @internal
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
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->_number;
    }

    /**
     * The type of vehicle. Example: "Snabbtåg".
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * The code for the operator who runs the vehicle. Example: 74.
     *
     * @return int
     */
    public function getOperatorCode(): int
    {
        return $this->_operatorCode;
    }

    /**
     * The URL for the operator whi runs the vehicle. Example: "http://www.sj.se"
     *
     * @return string
     */
    public function getOperatorUrl()
    {
        return $this->_operatorUrl;
    }

    /**
     * The name for the operator whi runs the vehicle. Example: "SJ"
     *
     * @return string
     */
    public function getOperatorName(): string
    {
        return $this->_operatorName;
    }

    /**
     * The line number of the vehicle, identifying the line on which it runs. Example: 41X.
     *
     * @return string
     */
    public function getLineNumber(): string
    {
        return $this->_number;
    }

    /**
     * Parse (a part of) an API response and store the result in this object.
     * @param array $json
     */
    private function parseApiResponse(array $json)
    {
        $this->_name = $json['name'];
        $this->_number = $json['num'];

        switch ($json['catCode']) {
            case 1:
            case 2:
            case 4:
                $this->_type = TransportType::TRAIN;
                break;
            case 3:
                $this->_type = TransportType::BUS;
                break;
            case 5:
                $this->_type = TransportType::METRO;
                break;
            case 6:
                $this->_type = TransportType::TRAM;
                break;
            case 7:
                $this->_type = TransportType::METRO;
                break;
            case 8:
                $this->_type = TransportType::SHIP;
                break;
        };

        $this->_operatorCode = $json['operatorCode'];
        $this->_operatorName = $json['operator'];
        $this->_operatorUrl = $json['operatorUrl'];
    }
}
