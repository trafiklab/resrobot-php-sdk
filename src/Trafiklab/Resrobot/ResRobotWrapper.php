<?php

use Trafiklab\Resrobot\Internal\ResRobotClient;
use Trafiklab\ResRobot\Model\RoutePlanningRequest;
use Trafiklab\ResRobot\Model\RoutePlanningResponse;
use Trafiklab\Resrobot\Model\TimeTableRequest;
use Trafiklab\Resrobot\Model\TimeTableResponse;
use Trafiklab\Resrobot\Model\TimeTableType;

class ResRobotWrapper
{

    private static $_instance;

    private $_key_reseplanerare;
    private $_key_stolptidstabeller;
    private $_resrobotClient;

    private function __construct($_resrobotClient = null)
    {
        // Private constructor for Singleton pattern
        $this->_resrobotClient = $_resrobotClient;
        if ($this->_resrobotClient == null) {
            $this->_resrobotClient = new ResRobotClient();
        }
    }

    public static function getInstance(): ResRobotWrapper
    {
        if (self::$_instance == null) {
            self::$_instance = new ResRobotWrapper();
        }
        return self::$_instance;
    }

    public function registerRoutePlanningApiKey(string $key): void
    {
        $this->_key_reseplanerare = $key;
    }

    public function registerTimeTablesApiKey(string $key): void
    {
        $this->_key_stolptidstabeller = $key;
    }


    public function registerUserAgent(string $userAgent): void
    {
        $this->_resrobotClient->setApplicationUserAgent($userAgent);
    }

    /**
     * @param TimeTableRequest $request
     *
     * @return TimeTableResponse
     * @throws Exception
     */
    public function getTimeTable(TimeTableRequest $request): TimeTableResponse
    {
        $this->requireValidTimeTablesKey();

        if ($request->getTimeTableType() == TimeTableType::DEPARTURES) {
            return $this->_resrobotClient->getDepartures($this->_key_stolptidstabeller, $request->getStopId(),
                $request->getDateTime(), $request->getVehicleFilter(), $request->getOperatorFilter());
        } else {
            if ($request->getTimeTableType() == TimeTableType::ARRIVALS) {
                return $this->_resrobotClient->getArrivals($this->_key_stolptidstabeller, $request->getStopId(),
                    $request->getDateTime(), $request->getVehicleFilter(), $request->getOperatorFilter());
            } else {
                throw new Exception("Invalid request", 400);
            }
        }
    }

    /**
     * @param RoutePlanningRequest $request
     *
     * @return RoutePlanningResponse
     * @throws Exception
     */
    public function getRoutePlanning(RoutePlanningRequest $request): RoutePlanningResponse
    {
        $this->requireValidRouteplannerKey();
        $this->_resrobotClient->getRoutePlanning($request);
        // TODO: implement
    }

    /**
     * @throws Exception
     */
    private function requireValidTimeTablesKey()
    {
        if ($this->_key_stolptidstabeller == null || empty($this->_key_stolptidstabeller)) {
            throw new Exception(
                "No Timetables API key configured. Obtain a free key at https://www.trafiklab.se/api", 403);
        }
    }

    /**
     * @throws Exception
     */
    private function requireValidRouteplannerKey()
    {
        if ($this->_key_stolptidstabeller == null || empty($this->_key_stolptidstabeller)) {
            throw new Exception(
                "No Routeplanner API key configured. Obtain a free key at https://www.trafiklab.se/api", 403);
        }
    }
}