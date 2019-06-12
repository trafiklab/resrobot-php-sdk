<?php

namespace Trafiklab\ResRobot;

use InvalidArgumentException;
use Trafiklab\Common\Model\Contract\PublicTransportApiWrapper;
use Trafiklab\Common\Model\Contract\RoutePlanningRequest;
use Trafiklab\Common\Model\Contract\RoutePlanningResponse;
use Trafiklab\Common\Model\Contract\StopLocationLookupRequest;
use Trafiklab\Common\Model\Contract\StopLocationLookupResponse;
use Trafiklab\Common\Model\Contract\TimeTableRequest;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Exceptions\InvalidKeyException;
use Trafiklab\Common\Model\Exceptions\InvalidRequestException;
use Trafiklab\Common\Model\Exceptions\InvalidStopLocationException;
use Trafiklab\Common\Model\Exceptions\KeyRequiredException;
use Trafiklab\Common\Model\Exceptions\QuotaExceededException;
use Trafiklab\Common\Model\Exceptions\RequestTimedOutException;
use Trafiklab\Common\Model\Exceptions\ServiceUnavailableException;
use Trafiklab\ResRobot\Internal\ResRobotClient;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningRequest;
use Trafiklab\ResRobot\Model\ResRobotStopLocationLookupRequest;
use Trafiklab\ResRobot\Model\ResRobotTimeTableRequest;

class ResRobotWrapper implements PublicTransportApiWrapper
{

    private $_key_reseplanerare;
    private $_key_stolptidstabeller;
    private $_resrobotClient;

    public function __construct()
    {
        $this->_resrobotClient = new ResRobotClient();
    }

    /**
     * Set the API key used for route-planning.
     *
     * @param string $apiKey The API key to use.
     */
    public function setRoutePlanningApiKey(?string $apiKey): void
    {
        $this->_key_reseplanerare = $apiKey;
    }

    /**
     * Set the API key used for looking up timetables.
     *
     * @param string $apiKey The API key to use.
     */
    public function setTimeTablesApiKey(?string $apiKey): void
    {
        $this->_key_stolptidstabeller = $apiKey;
    }

    /**
     * Set the API key used for looking up stop locations. For ResRobot this key is the same as the key used for
     * route-planning.
     *
     * @param string $apiKey The API key to use.
     */
    public function setStopLocationLookupApiKey(?string $apiKey): void
    {
        $this->_key_reseplanerare = $apiKey;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->_resrobotClient->setApplicationUserAgent($userAgent);
    }

    /**
     * @param ResRobotTimeTableRequest $request
     *
     * @return TimeTableResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getTimeTable(TimeTableRequest $request): TimeTableResponse
    {
        $this->requireValidTimeTablesKey();

        if (!$request instanceof ResRobotTimeTableRequest) {
            throw new InvalidArgumentException("ResRobot requires a ResRobotTimeTableRequest object");
        }

        return $this->_resrobotClient->getTimeTable($this->_key_stolptidstabeller, $request);
    }

    /**
     * @param ResRobotRoutePlanningRequest $request
     *
     * @return RoutePlanningResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getRoutePlanning(RoutePlanningRequest $request): RoutePlanningResponse
    {
        $this->requireValidRouteplannerKey();

        if (!$request instanceof ResRobotRoutePlanningRequest) {
            throw new InvalidArgumentException("ResRobot requires a ResRobotRoutePlanningRequest object");
        }

        return $this->_resrobotClient->getRoutePlanning($this->_key_reseplanerare, $request);
    }

    /**
     * Get a route-planning between two points.
     *
     * @param StopLocationLookupRequest $request The request object containing the query parameters.
     *
     * @return StopLocationLookupResponse The response from the API.
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws KeyRequiredException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     * @throws ServiceUnavailableException
     */
    public function lookupStopLocation(StopLocationLookupRequest $request): StopLocationLookupResponse
    {
        $this->requireValidRouteplannerKey();

        if (!$request instanceof ResRobotStopLocationLookupRequest) {
            throw new InvalidArgumentException("ResRobot requires a ResRobotStopLocationLookupRequest object");
        }

        return $this->_resrobotClient->lookupStopLocation($this->_key_reseplanerare, $request);
    }

    public function createTimeTableRequestObject(): TimeTableRequest
    {
        return new ResRobotTimeTableRequest();
    }

    public function createRoutePlanningRequestObject(): RoutePlanningRequest
    {
        return new ResRobotRoutePlanningRequest();
    }

    public function createStopLocationLookupRequestObject(): StopLocationLookupRequest
    {
        return new ResRobotStopLocationLookupRequest();
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