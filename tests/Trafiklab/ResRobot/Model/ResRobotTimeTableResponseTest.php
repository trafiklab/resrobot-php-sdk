<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use Trafiklab\ResRobot\TestCase;
use Trafiklab\Common\Model\Contract\WebResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableResponseTest extends TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode($this->readTestResource("ResRobot/validDeparturesReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $departureBoard = new ResRobotTimeTableResponse($dummyResponse, $validDepartures);

        self::assertNotNull($departureBoard->getTimetable());
        self::assertEquals(TimeTableType::DEPARTURES, $departureBoard->getType());

        self::assertEquals(10, count($departureBoard->getTimetable()));
        self::assertEquals($dummyResponse, $departureBoard->getOriginalApiResponse());
    }

    function testConstructor_validArrivalBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validDepartures = json_decode($this->readTestResource("ResRobot/validArrivalsReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $arrivalBoard = new ResRobotTimeTableResponse($dummyResponse, $validDepartures);

        self::assertNotNull($arrivalBoard->getTimetable());
        self::assertEquals(TimeTableType::ARRIVALS, $arrivalBoard->getType());

        self::assertEquals(11, count($arrivalBoard->getTimetable()));
    }
}
