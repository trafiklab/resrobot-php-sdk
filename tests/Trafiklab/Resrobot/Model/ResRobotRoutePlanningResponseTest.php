<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use PHPUnit\Framework\TestCase;
use Trafiklab\Common\Model\Contract\WebResponse;

class RoutePlanningReplyTest extends TestCase
{
    function testConstructor_validRoutePlanningReplyJson_shouldReturnCorrectObjectRepresentation()
    {
        $validRoutePlanning = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningReply.json"), true);
        $dummyResponse = $this->createMock(WebResponse::class);
        $routePlanningResponse = new ResRobotRoutePlanningResponse($dummyResponse, $validRoutePlanning);

        self::assertNotNull($routePlanningResponse->getTrips());
        self::assertEquals(6, count($routePlanningResponse->getTrips()));
        self::assertEquals($dummyResponse, $routePlanningResponse->getOriginalApiResponse());
    }

}
