<?php

namespace Trafiklab\Resrobot\Model;

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
     * @return mixed
     */
    public function getOperator() : string
    {
        return $this->_operator;
    }

    /**
     * @return mixed
     */
    public function getStopId() : string
    {
        return $this->_stopId;
    }

    /**
     * @return mixed
     */
    public function getStopName() : string
    {
        return $this->_stopName;
    }

    /**
     * @return mixed
     */
    public function getStopTime() : \DateTime
    {
        return $this->_stopTime;
    }

    /**
     * @return mixed
     */
    public function getTimeTableType() : int
    {
        return $this->_timeTableType;
    }

    /**
     * @return mixed
     */
    public function getDirection() : String
    {
        return $this->_direction;
    }

    /**
     * @return mixed
     */
    public function getLineName() : string
    {
        return $this->_lineName;
    }

    /**
     * @return mixed
     */
    public function getLineNumber() : int
    {
        return $this->_lineNumber;
    }

    private function parseApiResponse(array $json): void
    {
        $this->_stopId = $json['stopid'];
        $this->_stopName = $json['stop'];
        $this->_lineName = $json['name'];
        $this->_lineNumber = $json['transportNumber'];
        if ($this->_timeTableType == TimeTableType::DEPARTURES) {
            $this->_direction = $json['direction'];
        } else {
            $this->_direction = $json['origin'];
        }

        $this->_stopTime =
            \DateTime::createFromFormat("Y-m-d H:i:s",
                $json['date'] . ' ' . $json['time']);
        $this->_operator = $json['Product']['operator'];
    }


}
