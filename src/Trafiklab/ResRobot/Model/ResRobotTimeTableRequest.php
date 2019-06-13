<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use Trafiklab\Common\Model\Contract\TimeTableRequest;
use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableRequest extends ResRobotBaseRequest implements TimeTableRequest
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