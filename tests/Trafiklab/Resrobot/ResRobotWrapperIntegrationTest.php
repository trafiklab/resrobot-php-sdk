<?php

namespace Trafiklab\ResRobot;

use Exception;
use PHPUnit_Framework_TestCase;
use ResRobotWrapper;
use Trafiklab\Resrobot\Model\TimeTableRequest;
use Trafiklab\Resrobot\Model\TimeTableType;

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

        $departuresRequest = new TimeTableRequest();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::DEPARTURES);

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        self::assertEquals(TimeTableType::DEPARTURES, $response->getType());
        self::assertEquals("SL", $response->getTimetable()[0]->getOperator());
    }


    public function testGetArrivals()
    {
        if (empty($this->_TIMETABLES_API_KEY)) {
            $this->markTestIncomplete();
        }

        $departuresRequest = new TimeTableRequest();
        $departuresRequest->setStopId("740000001");
        $departuresRequest->setTimeTableType(TimeTableType::ARRIVALS);

        $resRobotWrapper = ResRobotWrapper::getInstance();
        $resRobotWrapper->registerUserAgent("SDK Integration tests");
        $resRobotWrapper->registerTimeTablesApiKey($this->_TIMETABLES_API_KEY);
        $response = $resRobotWrapper->getTimeTable($departuresRequest);

        self::assertEquals(TimeTableType::ARRIVALS, $response->getType());
        self::assertEquals("SL", $response->getTimetable()[0]->getOperator());
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

            foreach (explode(PHP_EOL,$testKeysFile ) as $line) {
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
