<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;


use DateTime;
use DateTimeZone;

abstract class ResRobotBaseRequest
{
    protected $_productFilter = [];
    protected $_dateTime;
    protected $_operatorFilter = [];

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
        if ($this->_dateTime == null) {
            return new DateTime('now', new DateTimeZone('Europe/Stockholm'));
        }
        return $this->_dateTime;
    }

    /**
     * @param mixed $dateTime
     */
    public function setDateTime(?DateTime $dateTime): void
    {
        $this->_dateTime = $dateTime;
        if ($this->_dateTime != null) {
            $this->_dateTime->setTimezone(new DateTimeZone('Europe/Stockholm'));
        }
    }

    /**
     * @param int $productCode
     */
    public function addTransportTypeToFilter(int $productCode): void
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