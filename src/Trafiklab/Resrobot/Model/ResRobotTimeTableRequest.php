<?php

namespace Trafiklab\Resrobot\Model;

use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableRequest extends ResRobotBaseRequest
{
    private $_timeTableType = TimeTableType::DEPARTURES;
    private $_stopId = "";

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
     * @return int
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