<?php


namespace Trafiklab\ResRobot\Model;


use DateTime;

class Stop
{
    private $_stopId;
    private $_stopName;
    private $_departureTime;
    private $_arrivalTime;
    private $_latitude;
    private $_longitude;

    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * @return mixed
     */
    public function getStopId(): string
    {
        return $this->_stopId;
    }

    /**
     * @return mixed
     */
    public function getStopName(): string
    {
        return $this->_stopName;
    }

    /**
     * @return mixed
     */
    public function getDepartureTime(): ?DateTime
    {
        return $this->_departureTime;
    }

    /**
     * @return mixed
     */
    public function getArrivalTime(): ?DateTime
    {
        return $this->_arrivalTime;
    }

    /**
     * @return mixed
     */
    public function getLatitude(): float
    {
        return $this->_latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude(): float
    {
        return $this->_longitude;
    }

    private function parseApiResponse(array $json)
    {
        $this->_stopId = $json['extId'];
        $this->_stopName = $json['name'];

        $this->_latitude = $json['lat'];
        $this->_longitude = $json['lon'];

        if (key_exists('depDate', $json)) {
            $this->_departureTime =
                DateTime::createFromFormat("Y-m-d H:i:s",
                    $json['depDate'] . ' ' . $json['depTime']);
        }
        if (key_exists('arrDate', $json)) {
            $this->_arrivalTime =
                DateTime::createFromFormat("Y-m-d H:i:s",
                    $json['arrDate'] . ' ' . $json['arrTime']);
        }

        if ($this->_departureTime == null && $this->_arrivalTime == null && key_exists('date', $json)) {
            // This is a backup solution designed to handle origin/destination of legs in case of a walking link.
            $this->_departureTime =
                DateTime::createFromFormat("Y-m-d H:i:s",
                    $json['date'] . ' ' . $json['time']);
            $this->_arrivalTime = $this->_departureTime;
        }

    }
}