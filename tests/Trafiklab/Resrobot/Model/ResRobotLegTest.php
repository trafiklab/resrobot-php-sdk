<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\RoutePlanningLegType;

class ResRobotLegTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validRoutePlanningLegJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(file_get_contents("./tests/Resources/ResRobot/validRoutePlanningLeg.json"), true);
        $leg = new ResRobotLeg($jsonArray);

        self::assertEquals("Stockholm Centralstation", $leg->getDeparture()->getStopName());
        self::assertEquals(740000001, $leg->getDeparture()->getStopId());
        self::assertEquals(16140, $leg->getDuration());
        self::assertEquals(new DateTime("2019-04-25 19:25:00"), $leg->getDeparture()->getScheduledDepartureTime());
        self::assertNull($leg->getDeparture()->getScheduledArrivalTime());
        self::assertEquals("Malmö Centralstation", $leg->getArrival()->getStopName());
        self::assertEquals("X2000", $leg->getNotes()[0]);
        self::assertEquals("Snabbtåg 547", $leg->getVehicle()->getName());
        self::assertEquals(8, count($leg->getIntermediaryStops()));
        self::assertEquals("Malmö Centralstation", $leg->getDirection());
        self::assertEquals(RoutePlanningLegType::VEHICLE_JOURNEY, $leg->getType());
    }

}