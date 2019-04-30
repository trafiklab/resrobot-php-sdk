<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;

class VehicleTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningVehicle.json"), true);
        $vehicle = new ResRobotVehicle($jsonArray);

        self::assertEquals("Regional Tåg 11154", $vehicle->getName());
        self::assertEquals("Regional Tåg", $vehicle->getType());
        self::assertEquals(11154, $vehicle->getNumber());
        self::assertEquals(300, $vehicle->getOperatorCode());
        self::assertEquals("Öresundståg", $vehicle->getOperatorName());
        self::assertEquals("http://www.oresundstag.se/", $vehicle->getOperatorUrl());
    }

}