<?php


namespace Trafiklab\ResRobot\Model;


use InvalidArgumentException;
use Trafiklab\Common\Model\Contract\StopLocationLookupEntry;
use Trafiklab\Common\Model\Enum\TransportType;
use Trafiklab\ResRobot\Model\Enum\ResRobotTransportType;

class ResRobotStopLocationLookupEntry implements StopLocationLookupEntry
{
    private $_id;
    private $_lat;
    private $_lon;
    private $_name;
    private $_products;
    private $_weight;

    /**
     * ResRobotStopLocationLookupEntry constructor.
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
     * Get the id of this stop area.
     *
     * @return string The id of this stop area.
     */
    public function getId(): string
    {
        return $this->_id;
    }

    /**
     * Get the name of this stop area.
     *
     * @return string The name of this stop area.
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * The longitude of this stop area.
     *
     * @return float The longitude of this stop area.
     */
    public function getLongitude(): float
    {
        return $this->_lon;
    }

    /**
     * The latitude of this stop area.
     *
     * @return float The latitude of this stop area.
     */
    public function getLatitude(): float
    {
        return $this->_lat;
    }

    /**
     * The sorting weight for this station. This can be determined by the number of vehicles stopping there, the
     * number of passengers, ...
     *
     * @return int The sorting weight for this station.
     */
    public function getWeight(): int
    {
        return $this->_weight;
    }

    /**
     * Check if a certain mode of transport stops at this stop location.
     *
     * @param string $transportType The type of transport, one of the constants in TransportType
     *
     * @return bool Whether or not the specified type of traffic can stop in this point. In case an API doesn't provide
     *              this information, it will always return true.
     *
     * @see TransportType
     */
    public function isStopLocationForTransportType(string $transportType): bool
    {
        switch ($transportType) {
            case TransportType::BUS:
                return (($this->_products & ResRobotTransportType::BUS_LOCAL)
                        == ResRobotTransportType::BUS_LOCAL)
                    || (($this->_products & ResRobotTransportType::BUS_LONG_DISTANCE)
                        == ResRobotTransportType::BUS_LONG_DISTANCE);
            case TransportType::METRO:
                return (($this->_products & ResRobotTransportType::SUBWAY)
                    == ResRobotTransportType::SUBWAY);
            case TransportType::TRAIN:
                return (($this->_products & ResRobotTransportType::TRAIN_HIGH_SPEED)
                        == ResRobotTransportType::TRAIN_HIGH_SPEED)
                    || (($this->_products & ResRobotTransportType::TRAIN_LOCAL)
                        == ResRobotTransportType::TRAIN_LOCAL)
                    || (($this->_products & ResRobotTransportType::TRAIN_REGIONAL_INTERCITY)
                        == ResRobotTransportType::TRAIN_REGIONAL_INTERCITY);
            case TransportType::TRAM:
                return (($this->_products & ResRobotTransportType::FERRIES_BOATS_CRUISES)
                    == ResRobotTransportType::TRAM_LIGHT_RAIL);
            case TransportType::SHIP:
                return (($this->_products & ResRobotTransportType::FERRIES_BOATS_CRUISES)
                    == ResRobotTransportType::FERRIES_BOATS_CRUISES);
            default:
                throw new InvalidArgumentException("TransportType should be a constant defined in TransportType");
        }
    }

    private function parseApiResponse(array $json)
    {
        $this->_id = $json['extId'];
        $this->_name = $json['name'];
        $this->_lat = $json['lat'];
        $this->_lon = $json['lon'];
        $this->_weight = $json['weight'];
        $this->_products = $json['products'];
    }
}