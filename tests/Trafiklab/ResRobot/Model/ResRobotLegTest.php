<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use Trafiklab\ResRobot\TestCase;
use Trafiklab\Common\Model\Enum\RoutePlanningLegType;

class ResRobotLegTest extends TestCase
{
    function testConstructor_validRoutePlanningLegJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode($this->readTestResource("ResRobot/validRoutePlanningLeg.json"), true);
        $leg = new ResRobotLeg($jsonArray);

        self::assertEquals("Solna station", $leg->getDeparture()->getStopName());
        self::assertEquals(740000759, $leg->getDeparture()->getStopId());
        self::assertEquals(420, $leg->getDuration());
        self::assertEquals(new DateTime("2021-09-23 22:30:00"), $leg->getDeparture()->getScheduledDepartureTime());
        self::assertNull($leg->getDeparture()->getScheduledArrivalTime());
        self::assertEquals("Stockholm City station", $leg->getArrival()->getStopName());
        self::assertEquals("Endast 2 klass", $leg->getNotes()[0]);
        self::assertEquals("Länstrafik - Tåg 41", $leg->getVehicle()->getName());
        self::assertEquals("41", $leg->getVehicle()->getLineNumber());
        self::assertEquals(1, count($leg->getIntermediaryStops()));
        self::assertEquals("Södertälje centrum station", $leg->getDirection());
        self::assertEquals(RoutePlanningLegType::VEHICLE_JOURNEY, $leg->getType());
    }

}