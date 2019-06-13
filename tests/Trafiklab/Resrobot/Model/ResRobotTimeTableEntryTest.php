<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableEntryTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validDeparturesReplyEntry.json"), true);
        $departureBoardEntry = new ResRobotTimeTableEntry($validDepartures, TimeTableType::DEPARTURES);

        self::assertEquals("SL", $departureBoardEntry->getOperator());
        self::assertEquals("Länstrafik -Tunnelbana 13", $departureBoardEntry->getLineName());
        self::assertEquals(13, $departureBoardEntry->getLineNumber());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $departureBoardEntry->getStopName());
        self::assertEquals("740020749", $departureBoardEntry->getStopId());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2019-04-16 15:08"),
            $departureBoardEntry->getScheduledStopTime());
        self::assertEquals("Alby T-bana (Botkyrka kn)", $departureBoardEntry->getDirection());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoardEntry->getTimeTableType());
    }

    function testConstructor_validArrivalBoardEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validArrivalsReplyEntry.json"), true);
        $arrivalBoardEntry = new ResRobotTimeTableEntry($validDepartures, TimeTableType::ARRIVALS);

        self::assertEquals("SL", $arrivalBoardEntry->getOperator());
        self::assertEquals("Länstrafik -Tunnelbana 19", $arrivalBoardEntry->getLineName());
        self::assertEquals(19, $arrivalBoardEntry->getLineNumber());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $arrivalBoardEntry->getStopName());
        self::assertEquals("740020749", $arrivalBoardEntry->getStopId());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2019-04-16 15:09"),
            $arrivalBoardEntry->getScheduledStopTime());
        self::assertEquals("Högdalen T-bana (Stockholm kn)", $arrivalBoardEntry->getDirection());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoardEntry->getTimeTableType());
    }
}
