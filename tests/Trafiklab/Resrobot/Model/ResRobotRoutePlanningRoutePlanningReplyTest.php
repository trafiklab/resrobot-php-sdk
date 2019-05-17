<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Contract\WebResponse;

class RoutePlanningReplyTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validRoutePlanningReplyJson_shouldReturnCorrectObjectRepresentation()
    {
        $validRoutePlanning = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $routePlanningResponse = new ResRobotRoutePlanningResponse($dummyResponse, $validRoutePlanning);

        self::assertNotNull($routePlanningResponse->getTrips());
        self::assertEquals(6, count($routePlanningResponse->getTrips()));
    }

}
