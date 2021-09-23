<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

/** @noinspection PhpUnhandledExceptionInspection */

namespace Trafiklab\ResRobot\Model;

use PHPUnit\Framework\TestCase;
use Trafiklab\Common\Model\Enum\TransportType;

class ResRobotVehicleTest extends TestCase
{
    function testConstructor_validVehicleJson_shouldReturnCorrectObjectRepresentation()
    {
        $jsonArray = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validRoutePlanningVehicle.json"), true);
        $vehicle = new ResRobotVehicle($jsonArray);

        self::assertEquals("Länstrafik - Tåg 41", $vehicle->getName());
        self::assertEquals(TransportType::TRAIN, $vehicle->getType());
        self::assertEquals(41, $vehicle->getNumber());
        self::assertEquals(275, $vehicle->getOperatorCode());
        self::assertEquals("SL", $vehicle->getOperatorName());
        self::assertNull($vehicle->getOperatorUrl());
    }

}