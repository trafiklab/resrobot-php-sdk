<?php

namespace Trafiklab\ResRobot\Model;

class TimeTableEntryTest extends \PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/validDeparturesReplyEntry.json"), true);
        $departureBoardEntry = new TimeTableEntry($validDepartures, TimeTableType::DEPARTURES);

        self::assertEquals("SL", $departureBoardEntry->getOperator());
        self::assertEquals("Länstrafik -Tunnelbana 13", $departureBoardEntry->getLineName());
        self::assertEquals(13, $departureBoardEntry->getLineNumber());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $departureBoardEntry->getStopName());
        self::assertEquals("740020749", $departureBoardEntry->getStopId());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2019-04-16 15:08"),
            $departureBoardEntry->getStopTime());
        self::assertEquals("Alby T-bana (Botkyrka kn)", $departureBoardEntry->getDirection());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoardEntry->getTimeTableType());
    }

    function testConstructor_validArrivalBoardEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/validArrivalsReplyEntry.json"), true);
        $arrivalBoardEntry = new TimeTableEntry($validDepartures, TimeTableType::ARRIVALS);

        self::assertEquals("SL", $arrivalBoardEntry->getOperator());
        self::assertEquals("Länstrafik -Tunnelbana 19", $arrivalBoardEntry->getLineName());
        self::assertEquals(19, $arrivalBoardEntry->getLineNumber());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $arrivalBoardEntry->getStopName());
        self::assertEquals("740020749", $arrivalBoardEntry->getStopId());
        self::assertEquals(\DateTime::createFromFormat("Y-m-d H:i", "2019-04-16 15:09"),
            $arrivalBoardEntry->getStopTime());
        self::assertEquals("Högdalen T-bana (Stockholm kn)", $arrivalBoardEntry->getDirection());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoardEntry->getTimeTableType());
    }
}
