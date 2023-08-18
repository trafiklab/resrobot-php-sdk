<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Model;

use Trafiklab\ResRobot\TestCase;
use Trafiklab\Common\Model\Enum\TransportType;

class ResRobotStopLocationLookupEntryTest extends TestCase
{
    function testConstructor_validStopLocationLookupEntryJson_shouldReturnCorrectObjectRepresentation()
    {
        $validEntry = json_decode(
            $this->readTestResource("ResRobot/validStopLocationLookupEntry.json"), true);
        $entry = new ResRobotStopLocationLookupEntry($validEntry);

        self::assertEquals("740000759", $entry->getId());
        self::assertEquals("Solna station", $entry->getName());
        self::assertEquals(18.010041, $entry->getLongitude());
        self::assertEquals(59.365104, $entry->getLatitude());
        self::assertEquals(6748, $entry->getWeight());
        self::assertEquals(true, $entry->isStopLocationForTransportType(TransportType::TRAIN));
        self::assertEquals(false, $entry->isStopLocationForTransportType(TransportType::TRAM));
        self::assertEquals(true, $entry->isStopLocationForTransportType(TransportType::BUS));
        self::assertEquals(false, $entry->isStopLocationForTransportType(TransportType::METRO));
        self::assertEquals(false, $entry->isStopLocationForTransportType(TransportType::SHIP));

    }
}