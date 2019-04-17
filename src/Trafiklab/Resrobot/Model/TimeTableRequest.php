<?php

namespace Trafiklab\Resrobot\Model;

use DateTime;
use DateTimeZone;

class TimeTableRequest
{
    private $_timeTableType;
    private $_stopId;
    private $_dateTime;
    private $_productFilter = [];
    private $_operatorFilter = [];

    /**
     * @return array
     */
    public function getOperatorFilter(): array
    {
        return $this->_operatorFilter;
    }

    /**
     * @return int
     */
    public function getVehicleFilter(): int
    {
        return array_sum($this->_productFilter);
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        if ($this->_dateTime == null){
            return new DateTime('now', new DateTimeZone('Europe/Stockholm'));
        }
        return $this->_dateTime;
    }

    /**
     * @param mixed $dateTime
     *

     */
    public function setDateTime(DateTime $dateTime): void
    {
        $this->_dateTime = $dateTime;
        $this->_dateTime->setTimezone(new DateTimeZone('Europe/Stockholm'));
    }

    /**
     * @return mixed
     */
    public function getStopId(): string
    {
        return $this->_stopId;
    }

    /**
     * @param mixed $stopId
     *

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
     *

     */
    public function setTimeTableType(int $timeTableType): void
    {
        $this->_timeTableType = $timeTableType;
    }

    /**
     * @param int $productCode
     *

     */
    public function addVehicleToFilter(int $productCode): void
    {
        $this->_productFilter[] = $productCode;

    }

    /**
     * @param int $operatorCode
     */
    public function addOperatorToFilter(int $operatorCode): void
    {
        $this->_operatorFilter[] = $operatorCode;

    }


}