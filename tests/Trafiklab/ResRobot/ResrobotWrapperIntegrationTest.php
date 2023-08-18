<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot;

use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Exceptions\DateTimeOutOfRangeException;
use Trafiklab\Common\Model\Exceptions\InvalidKeyException;
use Trafiklab\Common\Model\Exceptions\InvalidStopLocationException;
use Trafiklab\Common\Model\Exceptions\KeyRequiredException;
use Trafiklab\ResRobot\Model\Enum\ResRobotTransportType;

class ResrobotWrapperIntegrationTest extends TestCase
{
    private string $_API_KEY = "";

    public function __construct()
    {
        parent::__construct();
        $testKeysFromFile = $this->getTestKeysFromFile();

        if ($testKeysFromFile != null && key_exists('API_KEY', $testKeysFromFile)) {
            $this->_API_KEY = $testKeysFromFile['API_KEY'];
        }

        if (empty($this->_API_KEY)) {
            $this->_API_KEY = getenv('API_KEY');
        }
    }

    public function testGetDepartures_validParameters_shouldReturnResponse()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }
        sleep(1);
        $resRobotWrapper = new ResRobotWrapper();
        $departuresRequest = $resRobotWrapper->createTimeTableRequestObject();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        self::assertEquals(TimeTableType::DEPARTURES, $response->getType());
        self::assertFalse(empty($response->getTimetable()[0]->getOperator()));
        self::assertTrue(count($response->getTimetable()) > 1, "There should be more than one result in the response");

        $departuresRequest = $resRobotWrapper->createTimeTableRequestObject();
        $departuresRequest->setStopId("740020671"); // Arlanda buss
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);
        $departuresRequest->addTransportTypeToFilter(ResRobotTransportType::TRAIN_HIGH_SPEED); // Only trains

        $resRobotWrapper = new ResRobotWrapper();
        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        // Expect no results, as we asked trains from a bus stop.
        self::assertEquals(0, count($response->getTimetable()));
    }

    public function testGetDepartures_invalidStationId_shouldThrowException()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }
        sleep(1);
        $this->expectException(InvalidStopLocationException::class);

        $resRobotWrapper = new ResRobotWrapper();
        $departuresRequest = $resRobotWrapper->createTimeTableRequestObject();
        $departuresRequest->setStopId("7400001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey($this->_API_KEY);
        $resRobotWrapper->getTimeTable($departuresRequest);
    }


    public function testGetDepartures_invalidApiKey_shouldThrowException()
    {
        $this->expectException(InvalidKeyException::class);

        $resRobotWrapper = new ResRobotWrapper();
        $departuresRequest = $resRobotWrapper->createTimeTableRequestObject();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);


        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey("ABC0123");
        $resRobotWrapper->getTimeTable($departuresRequest);
    }

    public function testGetDepartures_missingApiKey_shouldThrowException()
    {
        sleep(1);
        $this->expectException(KeyRequiredException::class);

        $resRobotWrapper = new ResRobotWrapper();
        $departuresRequest = $resRobotWrapper->createTimeTableRequestObject();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey("");
        $resRobotWrapper->getTimeTable($departuresRequest);
    }

    public function testGetArrivals_validParameters_shouldReturnResponse()
    {
        sleep(1);
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $resRobotWrapper = new ResRobotWrapper();
        $arrivalsRequest = $resRobotWrapper->createTimeTableRequestObject();
        $arrivalsRequest->setStopId("740000001");
        $arrivalsRequest->setTimeTableType(TimeTableType::ARRIVALS);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getTimeTable($arrivalsRequest);

        self::assertEquals(TimeTableType::ARRIVALS, $response->getType());
        self::assertFalse(empty($response->getTimetable()[0]->getOperator()));

        $arrivalsRequest = $resRobotWrapper->createTimeTableRequestObject();
        $arrivalsRequest->setStopId("740020671"); // Arlanda buss
        $arrivalsRequest->setTimeTableType(TimeTableType::ARRIVALS);
        $arrivalsRequest->addOperatorToFilter(277); // Flygbussarna

        $resRobotWrapper = new ResRobotWrapper();
        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setTimeTablesApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getTimeTable($arrivalsRequest);

        foreach ($response->getTimetable() as $timeTableEntry) {
            self::assertEquals("Flygbussarna", $timeTableEntry->getOperator());
        }
    }


    /**
     * @throws Exception
     */
    public function testGetRoutePlanning_validParameters_shouldReturnResponse()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $queryTime = new DateTime();
        $queryTime->setTime(18, 0);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("740000002");
        $routePlanningRequest->setDateTime($queryTime);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getRoutePlanning($routePlanningRequest);

        self::assertTrue(count($response->getTrips()) > 0);
        $firstTripLegs = $response->getTrips()[0]->getLegs();
        self::assertEquals("740000001", $firstTripLegs[0]->getDeparture()->getStopId());
        self::assertEquals("740000002", end($firstTripLegs)->getArrival()->getStopId());
    }

    /**
     * @throws Exception
     */
    public function testGetRoutePlanning_WithVia_shouldReturnResponse()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $queryTime = new DateTime();
        $queryTime->setTime(18, 0);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("740000002");
        $routePlanningRequest->setDateTime($queryTime);
        $routePlanningRequest->setViaStopId("740000003");

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey($this->_API_KEY);
        $response = $resRobotWrapper->getRoutePlanning($routePlanningRequest);

        self::assertTrue(count($response->getTrips()) > 0);
        $firstTripLegs = $response->getTrips()[0]->getLegs();
        self::assertEquals("740000001", $firstTripLegs[0]->getDeparture()->getStopId());
        self::assertEquals("740000002", end($firstTripLegs)->getArrival()->getStopId());

        $foundViaStation = false;
        foreach ($response->getTrips()[0]->getLegs() as $leg) {
            if ($leg->getArrival()->getStopId() == "740000003") {
                $foundViaStation = true;
            }
        }
        self::assertTrue($foundViaStation);
    }

    public function testGetRoutePlanning_invalidStationId_shouldThrowException()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $this->expectException(InvalidStopLocationException::class);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("7400-02");

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey($this->_API_KEY);
        $resRobotWrapper->getRoutePlanning($routePlanningRequest);
    }

    public function testGetRoutePlanning_invalidApiKey_shouldThrowException()
    {
        $this->expectException(InvalidKeyException::class);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("740000002");

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey("ABC-012");
        $resRobotWrapper->getRoutePlanning($routePlanningRequest);
    }

    public function testGetRoutePlanning_missingApiKey_shouldThrowException()
    {
        $this->expectException(KeyRequiredException::class);

        $queryTime = new DateTime();
        $queryTime->setTime(18, 0);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("740000002");

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey("");
        $resRobotWrapper->getRoutePlanning($routePlanningRequest);
    }

    public function testGetRoutePlanning_invalidDate_shouldThrowException()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $this->expectException(DateTimeOutOfRangeException::class);

        $queryTime = new DateTime();
        $queryTime->setDate(2099, 1, 1);

        $resRobotWrapper = new ResRobotWrapper();
        $routePlanningRequest = $resRobotWrapper->createRoutePlanningRequestObject();
        $routePlanningRequest->setOriginStopId("740000001");
        $routePlanningRequest->setDestinationStopId("740000002");
        $routePlanningRequest->setDateTime($queryTime);

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setRoutePlanningApiKey($this->_API_KEY);
        $resRobotWrapper->getRoutePlanning($routePlanningRequest);
    }

    public function testLookupStopLocation_searchForTCentralen_shouldReturnMetroStation()
    {
        if (empty($this->_API_KEY)) {
            $this->markTestIncomplete();
        }

        $resRobotWrapper = new ResRobotWrapper();
        $lookupRequest = $resRobotWrapper->createStopLocationLookupRequestObject();
        $lookupRequest->setSearchQuery("t-centralen");

        $resRobotWrapper->setUserAgent("SDK Integration tests");
        $resRobotWrapper->setStopLocationLookupApiKey($this->_API_KEY);
        $response = $resRobotWrapper->lookupStopLocation($lookupRequest);

        self::assertEquals("740020749", $response->getFoundStopLocations()[0]->getId());
        self::assertEquals("T-Centralen T-bana (Stockholm kn)", $response->getFoundStopLocations()[0]->getName());
    }

    /**
     * Read test keys from a .testkeys file.
     *
     * @return array
     */
    private function getTestKeysFromFile(): array
    {
        if (!file_exists(".testkeys")) {
            return [];
        }

        try {
            $testKeys = [];
            $testKeysFile = file_get_contents(".testkeys");

            foreach (explode(PHP_EOL, $testKeysFile) as $line) {
                if (empty($line) || strpos($line, '#') === 0 || strpos($line, '=') === false) {
                    continue;
                }

                $keyvalue = explode('=', $line);
                $testKeys[$keyvalue[0]] = $keyvalue[1];
            }

            return $testKeys;
        } catch (Exception $exception) {
            return [];
        }
    }
}
