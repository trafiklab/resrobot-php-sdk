<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use PHPUnit\Framework\TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Enum\TransportType;

class ResRobotTimeTableEntryTest extends TestCase
{
    /**
     * @throws \Trafiklab\Common\Model\Exceptions\TrafiklabSdkException
     */
    function testConstructor_validDepartureBoardEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validDeparturesReplyEntry.json"), true);
        $departureBoardEntry = new ResRobotTimeTableEntry($validDepartures, TimeTableType::DEPARTURES);

        self::assertEquals("SL", $departureBoardEntry->getOperator());
        self::assertEquals("Länstrafik -Tunnelbana 10", $departureBoardEntry->getLineName());
        self::assertEquals(10, $departureBoardEntry->getLineNumber());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $departureBoardEntry->getStopName());
        self::assertEquals("740020749", $departureBoardEntry->getStopId());
        self::assertEquals("6", $departureBoardEntry->getPlatform());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2021-09-23 16:49"),
            $departureBoardEntry->getScheduledStopTime());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2021-09-24 16:50"),
            $departureBoardEntry->getEstimatedStopTime());
        self::assertEquals("Kungsträdgården T-bana (Stockholm kn)", $departureBoardEntry->getDirection());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoardEntry->getTimeTableType());
        self::assertEquals(TransportType::METRO, $departureBoardEntry->getTransportType());
    }

    /**
     * @throws \Trafiklab\Common\Model\Exceptions\TrafiklabSdkException
     */
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
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2021-09-23 16:37"),
            $arrivalBoardEntry->getScheduledStopTime());
        self::assertEquals("Hagsätra T-bana (Stockholm kn)", $arrivalBoardEntry->getDirection());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoardEntry->getTimeTableType());
    }
}
