<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use Trafiklab\Common\Model\Contract\TimeTableEntry;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Enum\TransportType;
use Trafiklab\Common\Model\Exceptions\TrafiklabSdkException;

/**
 * An entry in a timetable, describing a single departure or arrival of a vehicle at a stoplocation.
 *
 * @package Trafiklab\ResRobot\Model
 */
class ResRobotTimeTableEntry implements TimeTableEntry
{
    private $_stopId;
    private $_stopName;
    private $_lineName;
    private $_direction;
    private $_lineNumber;
    private $_stopTime;
    private $_timeTableType;
    private $_operator;
    private $_transportType;

    /**
     * ResRobotTimeTableEntry constructor.
     *
     * @param array $json
     * @param int   $type
     *
     * @internal
     * @throws TrafiklabSdkException
     */
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
     * The type of timetable in which this entry resides, either arrivals or departures.
     *
     * @return int
     */
    public function getTimeTableType(): int
    {
        return $this->_timeTableType;
    }

    /**
     * The direction of the vehicle stopping at this time at this stop location. In case of a vehicle departing, this
     * is the destination of the vehicle. In case of a vehicle arriving, this is the origin of the vehicle.
     *
     * @return string
     */
    public function getDirection(): string
    {
        return $this->_direction;
    }

    /**
     * The name of the line on which the vehicle is driving.
     *
     * @return string
     */
    public function getLineName(): string
    {
        return $this->_lineName;
    }

    /**
     * The number of the line on which the vehicle is driving.
     *
     * @return string
     */
    public function getLineNumber(): string
    {
        return $this->_lineNumber;
    }

    /**
     * The time at which the vehicle will arrive at the stop area. This can be an interval (5 min) or a time (18:00)
     * depending on the operator and data source.
     *
     * @return string
     */
    public function getDisplayTime(): string
    {
        return $this->_stopTime->format('H:i');
    }

    /**
     * The time at which the vehicle stops at the stop location, including possible delays.
     *
     * @return DateTime
     */
    public function getScheduledStopTime(): DateTime
    {
        return $this->_stopTime;
    }

    /**
     * Get the number of the vehicle.
     *
     * @return string
     */
    public function getTripNumber(): ?string
    {
        return null;
    }

    public function getTransportType(): string
    {
        return $this->_transportType;
    }

    /**
     * Parse (a part of) an API response and store the result in this object.
     *
     * @param array $json
     *
     * @throws TrafiklabSdkException
     */
    private function parseApiResponse(array $json): void
    {
        if (!key_exists('stopExtId', $json)) {
            throw new TrafiklabSdkException("The server responded with an invalid JSON response.
             stopExtId is missing.", 500);
        }

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
        $this->parseTransportType($json);

    }

    /**
     * Parse the transport type for this entry.
     *
     * @param array $json
     */
    private function parseTransportType(array $json): void
    {
        /*
                * Possible transport categories:
                * 'BLT': Local bus
                * 'BAX': Airport bus
                * 'BXB': Express bus
                * 'BRE': Regional bus
                * 'SLT': Local tram
                * 'JLT': Local train
                * 'JRE': Regional train
                * 'JAX': Airport train
                * 'JIC': IC train
                * 'JBL':
                * 'JNT': Night train
                * 'ULT': Local metro
                * 'FLT': Local ferry
                */

        switch (substr($json['transportCategory'], 0, 1)) {
            case 'B':
                $this->_transportType = TransportType::BUS;
                break;
            case 'S':
                $this->_transportType = TransportType::TRAM;
                break;
            case 'J':
                $this->_transportType = TransportType::TRAIN;
                break;
            case 'U':
                $this->_transportType = TransportType::METRO;
                break;
            case 'F':
                $this->_transportType = TransportType::SHIP;
                break;
        }
    }
}
