<?php

namespace Trafiklab\Resrobot\Model;

use DateTime;

/**
 * An entry in a timetable, describing a single departure or arrival of a vehicle at a stoplocation.
 *
 * @package Trafiklab\Resrobot\Model
 */
class TimeTableEntry
{
    private $_stopId;
    private $_stopName;
    private $_lineName;
    private $_direction;
    private $_lineNumber;
    private $_stopTime;
    private $_timeTableType;
    private $_operator;

    public function __construct(array $json, int $type)
    {
        $this->_timeTableType = $type;
        $this->parseApiResponse($json);
    }

    /**
     * The operator of the vehicle.
     *
     * @return string
     */
    public function getOperator(): string
    {
        return $this->_operator;
    }

    /**
     * The Rikshållplats-ID for the stop location.
     *
     * @return string
     */
    public function getStopId(): string
    {
        return $this->_stopId;
    }

    /**
     * The name of the stop at which the vehicle stops.
     *
     * @return string
     */
    public function getStopName(): string
    {
        return $this->_stopName;
    }

    /**
     * The time at which the vehicle stops at the stop location, including possible delays.
     *
     * @return DateTime
     */
    public function getStopTime(): DateTime
    {
        return $this->_stopTime;
    }

    /**
     * The type of timetable in which this entry resides, either arrivals or departures.
     * @return int
     */
    public function getTimeTableType(): int
    {
        return $this->_timeTableType;
    }

    /**
     * The direction of the vehicle stopping at this time at this stop location. In case of a vehicle departing, this
     * is the destination of the vehicle. In case of a vehicle arriving, this is the origin of the vehicle.
     * @return string
     */
    public function getDirection(): string
    {
        return $this->_direction;
    }

    /**
     * The name of the line on which the vehicle is driving.
     * @return string
     */
    public function getLineName(): string
    {
        return $this->_lineName;
    }

    /**
     * The number of the line on which the vehicle is driving.
     * @return int
     */
    public function getLineNumber(): int
    {
        return $this->_lineNumber;
    }

    private function parseApiResponse(array $json): void
    {
        $this->_stopId = $json['stopExtId'];
        $this->_stopName = $json['stop'];
        $this->_lineName = $json['name'];
        $this->_lineNumber = $json['transportNumber'];
        if ($this->_timeTableType == TimeTableType::DEPARTURES) {
            $this->_direction = $json['direction'];
        } else {
            $this->_direction = $json['origin'];
        }

        $this->_stopTime =
            DateTime::createFromFormat("Y-m-d H:i:s",
                $json['date'] . ' ' . $json['time']);
        $this->_operator = $json['Product']['operator'];
    }


}
