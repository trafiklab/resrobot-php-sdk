<?php

namespace Trafiklab\ResRobot;

use DateTime;
use Exception;
use PHPUnit_Framework_TestCase;
use ResRobotWrapper;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Resrobot\Model\Enum\ResRobotTransportType;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningRequest;
use Trafiklab\Resrobot\Model\ResRobotTimeTableRequest;

class ResRobotWrapperIntegrationTest extends PHPUnit_Framework_TestCase
{
    private $_TIMETABLES_API_KEY = "";
    private $_ROUTEPLANNING_API_KEY = "";

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $keys = $this->getTestKeys();
        $this->_TIMETABLES_API_KEY = $keys['TIMETABLES_API_KEY'];
        $this->_ROUTEPLANNING_API_KEY = $keys['ROUTEPLANNING_API_KEY'];
    }

    public function testGetDepartures()
    {
        if (empty($this->_TIMETABLES_API_KEY)) {
            $this->markTestIncomplete();
        }

        $departuresRequest = new ResRobotTimeTableRequest();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        self::assertEquals(TimeTableType::DEPARTURES, $response->getType());
        self::assertFalse(empty($response->getTimetable()[0]->getOperator()));


        $departuresRequest = new ResRobotTimeTableRequest();
        $departuresRequest->setStopId("740020671"); // Arlanda buss
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);
        $departuresRequest->addTransportTypeToFilter(ResRobotTransportType::TRAIN_HIGH_SPEED); // Only trains

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        // Expect no results, as we asked trains from a bus stop.
        self::assertEquals(0, count($response->getTimetable()));
    }


    public function testGetArrivals()
    {
        if (empty($this->_TIMETABLES_API_KEY)) {
            $this->markTestIncomplete();
        }

        $arrivalsRequest = new ResRobotTimeTableRequest();
        $arrivalsRequest->setStopId("740000001");
        $arrivalsRequest->setTimeTableType(TimeTableType::ARRIVALS);

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($arrivalsRequest);

        self::assertEquals(TimeTableType::ARRIVALS, $response->getType());
        self::assertFalse(empty($response->getTimetable()[0]->getOperator()));

        $arrivalsRequest = new ResRobotTimeTableRequest();
        $arrivalsRequest->setStopId("740020671"); // Arlanda buss
        $arrivalsRequest->setTimeTableType(TimeTableType::ARRIVALS);
        $arrivalsRequest->addOperatorToFilter(277); // Flygbussarna

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($arrivalsRequest);

        foreach ($response->getTimetable() as $timeTableEntry) {
            self::assertEquals("Flygbussarna", $timeTableEntry->getOperator());
        }
    }


    /**
     * @throws Exception
     */
    public function testGetRoutePlanning()
    {
        if (empty($this->_TIMETABLES_API_KEY)) {
            $this->markTestIncomplete();
        }

        $queryTime = new DateTime();
        $queryTime->setTime(18, 0);

        $routePlanningRequest = new ResRobotRoutePlanningRequest();
        $routePlanningRequest->setOriginId("740000001");
        $routePlanningRequest->setDestinationId("740000002");
        $routePlanningRequest->setDateTime($queryTime);

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerRoutePlanningApiKey($this->_ROUTEPLANNING_API_KEY);
        $response = $resRobotWrapper->getRoutePlanning($routePlanningRequest);

        self::assertTrue(count($response->getTrips()) > 0);
        $firstTripLegs = $response->getTrips()[0]->getLegs();
        self::assertEquals("740000001", $firstTripLegs[0]->getOrigin()->getStopId());
        self::assertEquals("740000002", end($firstTripLegs)->getDestination()->getStopId());
    }

    /**
     * @throws Exception
     */
    public function testGetRoutePlanningWithVia()
    {
        if (empty($this->_TIMETABLES_API_KEY)) {
            $this->markTestIncomplete();
        }

        $queryTime = new DateTime();
        $queryTime->setTime(18, 0);

        $routePlanningRequest = new ResRobotRoutePlanningRequest();
        $routePlanningRequest->setOriginId("740000001");
        $routePlanningRequest->setDestinationId("740000002");
        $routePlanningRequest->setDateTime($queryTime);
        $routePlanningRequest->setViaId("740000003");

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerRoutePlanningApiKey($this->_ROUTEPLANNING_API_KEY);
        $response = $resRobotWrapper->getRoutePlanning($routePlanningRequest);

        self::assertTrue(count($response->getTrips()) > 0);
        $firstTripLegs = $response->getTrips()[0]->getLegs();
        self::assertEquals("740000001", $firstTripLegs[0]->getOrigin()->getStopId());
        self::assertEquals("740000002", end($firstTripLegs)->getDestination()->getStopId());

        $foundViaStation = false;
        foreach ($response->getTrips()[0]->getLegs() as $leg) {
            if ($leg->getDestination()->getStopId() == "740000003") {
                $foundViaStation = true;
            }
        }
        self::assertTrue($foundViaStation);
    }


    /**
     * Read test keys from a .testkeys file.
     *
     * @return array
     */
    private function getTestKeys(): array
    {

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
