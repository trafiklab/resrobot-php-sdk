<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;


use DateTime;
use Trafiklab\ResRobot\TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\ResRobot\Model\Enum\ResRobotTransportType;

class ResRobotTimeTableRequestTest extends TestCase
{

    function testSetType()
    {
        $request = new ResRobotTimeTableRequest();
        $request->setTimeTableType(TimeTableType::DEPARTURES);
        self::assertEquals(TimeTableType::DEPARTURES, $request->getTimeTableType());

        $request->setTimeTableType(TimeTableType::ARRIVALS);
        self::assertEquals(TimeTableType::ARRIVALS, $request->getTimeTableType());
    }

    function testSetStopId()
    {
        $request = new ResRobotTimeTableRequest();
        $request->setStopId("ABC012");
        self::assertEquals("ABC012", $request->getStopId());

        $request->setStopId("");
        self::assertEquals("", $request->getStopId());
    }

    function testSetDateTime()
    {
        $request = new ResRobotTimeTableRequest();
        $now = new DateTime();
        $request->setDateTime($now);
        self::assertEquals($now, $request->getDateTime());
    }

    function testSetProductFilter()
    {
        $request = new ResRobotTimeTableRequest();
        $request->addTransportTypeToFilter(ResRobotTransportType::TRAIN_LOCAL);
        self::assertEquals(ResRobotTransportType::TRAIN_LOCAL, $request->getVehicleFilter());

        $request->addTransportTypeToFilter(ResRobotTransportType::BUS_LOCAL);
        self::assertEquals(ResRobotTransportType::TRAIN_LOCAL + ResRobotTransportType::BUS_LOCAL,
            $request->getVehicleFilter());
    }

    function testSetOperatorFilter()
    {
        $request = new ResRobotTimeTableRequest();
        $request->addOperatorToFilter(253);
        self::assertEquals([253], $request->getOperatorFilter());

        $request->addOperatorToFilter(256);
        self::assertEquals([253,256], $request->getOperatorFilter());
    }

}
