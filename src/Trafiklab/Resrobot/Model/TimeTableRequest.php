<?php

namespace Trafiklab\Resrobot\Model;

class TimeTableRequest extends ResRobotBaseRequest
{
    private $_timeTableType;
    private $_stopId;

    /**
     * @return mixed
     */
    public function getStopId(): string
    {
        return $this->_stopId;
    }

    /**
     * @param mixed $stopId
     */
    public function setStopId(string $stopId): void
    {
        $this->_stopId = $stopId;
    }

    /**
     * @return TimeTableType
     */
    public function getTimeTableType(): int
    {
        return $this->_timeTableType;
    }

    /**
     * @param mixed $timeTableType
     */
    public function setTimeTableType(int $timeTableType): void
    {
        $this->_timeTableType = $timeTableType;
    }


}