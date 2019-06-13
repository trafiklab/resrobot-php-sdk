<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Enum\TransportType;
use Trafiklab\ResRobot\Model\ResRobotStopLocationLookupEntry;

class ResRobotStopLocationLookupEntryTest extends PHPUnit_Framework_TestCase
{
    function testConstructor_validStopLocationLookupEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validEntry = json_decode(
            file_get_contents("./tests/Resources/ResRobot/validStopLocationLookupEntry.json"), true);
        $entry = new ResRobotStopLocationLookupEntry($validEntry);

        self::assertEquals("740098000", $entry->getId());
        self::assertEquals("STOCKHOLM", $entry->getName());
        self::assertEquals(18.058151, $entry->getLongitude());
        self::assertEquals(59.330136, $entry->getLatitude());
        self::assertEquals(32767, $entry->getWeight());
        self::assertEquals(true, $entry->isStopLocationForTransportType(TransportType::TRAIN));
        self::assertEquals(false, $entry->isStopLocationForTransportType(TransportType::TRAM));
        self::assertEquals(true, $entry->isStopLocationForTransportType(TransportType::BUS));
        self::assertEquals(true, $entry->isStopLocationForTransportType(TransportType::METRO));
        self::assertEquals(false, $entry->isStopLocationForTransportType(TransportType::SHIP));

    }
}