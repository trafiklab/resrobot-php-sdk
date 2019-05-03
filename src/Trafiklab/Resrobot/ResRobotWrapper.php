<?php

use Trafiklab\Common\Model\Contract\RoutePlanningResponse;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Exceptions\InvalidKeyException;
use Trafiklab\Common\Model\Exceptions\InvalidRequestException;
use Trafiklab\Common\Model\Exceptions\InvalidStoplocationException;
use Trafiklab\Common\Model\Exceptions\KeyRequiredException;
use Trafiklab\Common\Model\Exceptions\QuotaExceededException;
use Trafiklab\Common\Model\Exceptions\RequestTimedOutException;
use Trafiklab\Resrobot\Internal\ResRobotClient;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningRequest;
use Trafiklab\Resrobot\Model\ResRobotTimeTableRequest;

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
     * @param ResRobotTimeTableRequest $request
     *
     * @return TimeTableResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStoplocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getTimeTable(ResRobotTimeTableRequest $request): TimeTableResponse
    {
        $this->requireValidTimeTablesKey();
        return $this->_resrobotClient->getTimeTable($this->_key_stolptidstabeller, $request);
    }

    /**
     * @param ResRobotRoutePlanningRequest $request
     *
     * @return RoutePlanningResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStoplocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getRoutePlanning(ResRobotRoutePlanningRequest $request): RoutePlanningResponse
    {
        $this->requireValidRouteplannerKey();
        return $this->_resrobotClient->getRoutePlanning($this->_key_reseplanerare, $request);
    }

    /**
     * @throws KeyRequiredException
     */
    private function requireValidTimeTablesKey()
    {
        if ($this->_key_stolptidstabeller == null || empty($this->_key_stolptidstabeller)) {
            throw new KeyRequiredException("");
        }
    }

    /**
     * @throws KeyRequiredException
     */
    private function requireValidRouteplannerKey()
    {
        if ($this->_key_reseplanerare == null || empty($this->_key_reseplanerare)) {
            throw new KeyRequiredException("");
        }
    }
}