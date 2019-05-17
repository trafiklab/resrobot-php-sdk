<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Contract\WebResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableResponseTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/ResRobot/validDeparturesReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $departureBoard = new ResRobotTimeTableResponse($dummyResponse, $validDepartures);

        self::assertNotNull($departureBoard->getTimetable());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoard->getType());

        self::assertEquals(25, count($departureBoard->getTimetable()));
    }

    function testConstructor_validArrivalBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode(file_get_contents("./tests/Resources/ResRobot/validArrivalsReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $arrivalBoard = new ResRobotTimeTableResponse($dummyResponse, $validDepartures);

        self::assertNotNull($arrivalBoard->getTimetable());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoard->getType());

        self::assertEquals(25, count($arrivalBoard->getTimetable()));
    }
}
