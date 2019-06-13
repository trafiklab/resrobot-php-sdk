<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;

class ResRobotTripTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validTripJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningTrip.json"), true);
        $trip = new ResRobotTrip($jsonArray);
        self::assertNotNull($trip->getLegs());
        self::assertEquals(3, count($trip->getLegs()));
        self::assertEquals(42000, $trip->getDuration());
        self::assertEquals(740000001, $trip->getDeparture()->getStopId());
        self::assertEquals(740000002, $trip->getArrival()->getStopId());
    }

}