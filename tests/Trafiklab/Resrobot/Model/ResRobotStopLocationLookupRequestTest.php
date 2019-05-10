<?php

namespace Trafiklab\ResRobot\Model;

use PHPUnit_Framework_TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Enum\TransportType;
use Trafiklab\Sl\Model\ResRobotStopLocationLookupEntry;
use Trafiklab\Sl\Model\ResRobotStopLocationLookupResponse;

class ResRobotStopLocationLookupRequestTest extends PHPUnit_Framework_TestCase
{
    function testGettersSetters_createNewRequest_shouldReturnCorrectDefaultsOrSetValues()
    {
        $request = new ResRobotStopLocationLookupRequest();
        $request->setSearchQuery("Stockholm");

        // Test defaults
        self::assertEquals("sv", $request->getLanguage());
        self::assertEquals(10, $request->getMaxNumberOfResults());

        $request->setLanguage("en");
        $request->setMaxNumberOfResults(20);

        self::assertEquals("Stockholm", $request->getSearchQuery());
        self::assertEquals("en", $request->getLanguage());
        self::assertEquals(20, $request->getMaxNumberOfResults());

    }
}