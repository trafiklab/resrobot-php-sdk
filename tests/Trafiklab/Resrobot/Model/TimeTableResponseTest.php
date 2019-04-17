<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use Trafiklab\Resrobot\Model\TimeTableResponse;
use Trafiklab\Resrobot\Model\TimeTableType;

class TimeTableResponseTest extends \PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/validDeparturesReply.json"), true);
        $departureBoard = new TimeTableResponse($validDepartures);

        self::assertNotNull($departureBoard->getTimetable());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoard->getType());

        self::assertEquals(25,count($departureBoard->getTimetable()));
    }

    function testConstructor_validArrivalBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/validArrivalsReply.json"), true);
        $arrivalBoard = new TimeTableResponse($validDepartures);

        self::assertNotNull($arrivalBoard->getTimetable());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoard->getType());

        self::assertEquals(25,count($arrivalBoard->getTimetable()));
    }
}
