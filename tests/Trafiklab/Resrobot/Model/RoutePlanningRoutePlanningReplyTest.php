<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use DateTime;
use PHPUnit_Framework_TestCase;

class RoutePlanningReplyTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validDepartureBoardJson_shouldReturnCorrectObjectRepresentation()
    {
        $validRoutePlanning = json_decode(file_get_contents("./tests/Resources/validRoutePlanningReply.json"), true);
        $routePlanningResponse = new RoutePlanningResponse($validRoutePlanning);

        self::assertNotNull($routePlanningResponse->getTrips());
        self::assertEquals(6, count($routePlanningResponse->getTrips()));
    }

}
