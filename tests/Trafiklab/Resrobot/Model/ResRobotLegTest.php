<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;

class ResRobotLegTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validRoutePlanningLegJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(file_get_contents("./tests/Resources/ResRobot/validRoutePlanningLeg.json"), true);
        $leg = new ResRobotLeg($jsonArray);

        self::assertEquals("Stockholm Centralstation", $leg->getOrigin()->getStopName());
        self::assertEquals(740000001, $leg->getOrigin()->getStopId());
        self::assertEquals(new DateTime("2019-04-25 19:25:00"), $leg->getOrigin()->getScheduledDepartureTime());
        self::assertNull($leg->getOrigin()->getScheduledArrivalTime());
        self::assertEquals("Malmö Centralstation", $leg->getDestination()->getStopName());
        self::assertEquals("X2000", $leg->getNotes()[0]);
        self::assertEquals("Snabbtåg 547", $leg->getVehicle()->getName());
        self::assertEquals(8, count($leg->getIntermediaryStops()));
    }

}