<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;

class TripTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(file_get_contents("./tests/Resources/validRoutePlanningTrip.json"), true);
        $trip = new Trip($jsonArray);
        self::assertNotNull($trip->getLegs());
        self::assertEquals(3, count($trip->getLegs()));
    }

}