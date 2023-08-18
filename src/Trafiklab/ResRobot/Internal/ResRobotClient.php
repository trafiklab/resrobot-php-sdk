<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 */

namespace Trafiklab\ResRobot\Internal;

use Trafiklab\Common\Internal\CurlWebClient;
use Trafiklab\Common\Internal\WebClient;
use Trafiklab\Common\Internal\WebResponseImpl;
use Trafiklab\Common\Model\Contract\StopLocationLookupRequest;
use Trafiklab\Common\Model\Contract\StopLocationLookupResponse;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Enum\RoutePlanningSearchType;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Common\Model\Exceptions\DateTimeOutOfRangeException;
use Trafiklab\Common\Model\Exceptions\InvalidKeyException;
use Trafiklab\Common\Model\Exceptions\InvalidRequestException;
use Trafiklab\Common\Model\Exceptions\InvalidStopLocationException;
use Trafiklab\Common\Model\Exceptions\QuotaExceededException;
use Trafiklab\Common\Model\Exceptions\RequestTimedOutException;
use Trafiklab\Common\Model\Exceptions\ServiceUnavailableException;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningRequest;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningResponse;
use Trafiklab\ResRobot\Model\ResRobotStopLocationLookupResponse;
use Trafiklab\ResRobot\Model\ResRobotTimeTableRequest;
use Trafiklab\ResRobot\Model\ResRobotTimeTableResponse;

/**
 * @internal Builds requests and gets data.
 * @package  Trafiklab\ResRobot\Internal
 */
class ResRobotClient
{

    public const ROOT = "https://api.resrobot.se/v2.1";
    public const DEPARTURES_ENDPOINT = self::ROOT . "/departureBoard";
    public const ARRIVALS_ENDPOINT = self::ROOT . "/arrivalBoard";
    public const TRIPS_ENDPOINT = self::ROOT . "/trip";
    public const PLATSUPPSLAG_ENDPOINT = self::ROOT . "/location.name";
    public const SDK_USER_AGENT = "Trafiklab/ResRobot-php-sdk";

    const API_NAME_RESROBOT_ROUTEPLANNER = "ResRobot reseplanerare";
    const API_NAME_RESROBOT_TIMETABLES = "ResRobot stolptidtabeller";
    const API_NAME_RESROBOT_FINDSTOPLOCATION = "ResRobot platsuppslag";

    private $applicationUserAgent = "Unknown";

    /**
     * @var WebClient
     */
    private $_webClient;

    public function __construct(WebClient $webClient = null)
    {
        $this->_webClient = $webClient;
        if ($webClient == null) {
            $this->_webClient = new CurlWebClient(self::SDK_USER_AGENT);
        }
    }


    /**
     * @param string                   $key     The API key to use.
     * @param ResRobotTimeTableRequest $request The request object holding all parameters.
     *
     * @return TimeTableResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getTimeTable(string $key, ResRobotTimeTableRequest $request): TimeTableResponse
    {

        $endpoint = self::DEPARTURES_ENDPOINT;
        if ($request->getTimeTableType() == TimeTableType::ARRIVALS) {
            $endpoint = self::ARRIVALS_ENDPOINT;
        }

        $parameters = [
            "accessId" => $key,
            "id"       => $request->getStopId(),
            "date"     => $request->getDateTime()->format("Y-m-d"),
            "time"     => $request->getDateTime()->format("H:i"),
            "lang"     => $request->getLanguage(),
            "format"   => "json",
            "passlist" => "0",
        ];

        if ($request->getVehicleFilter() > 0) {
            $parameters['products'] = $request->getVehicleFilter();
        }

        if ($request->getOperatorFilter() != null) {
            $parameters['operators'] = join(',', $request->getOperatorFilter());
        }

        $response = $this->_webClient->makeRequest($endpoint, $parameters);
        $json = json_decode($response->getResponseBody(), true);

        $this->validateResRobotResponse(self::API_NAME_RESROBOT_TIMETABLES, $response, $json);
        return new ResRobotTimeTableResponse($response, $json);
    }

    /**
     * Get a route planning from A to B.
     *
     * @param string                       $key     The API key to use.
     * @param ResRobotRoutePlanningRequest $request The request object holding all parameters.
     *
     * @return ResRobotRoutePlanningResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     */
    public function getRoutePlanning(string $key, ResRobotRoutePlanningRequest $request): ResRobotRoutePlanningResponse
    {
        $searchForArrival = "0";
        if ($request->getRoutePlanningSearchType() == RoutePlanningSearchType::ARRIVE_AT_SPECIFIED_TIME) {
            $searchForArrival = "1";
        }

        $parameters = [
            "accessId"         => $key,
            "originId"         => $request->getOriginStopId(),
            "destId"           => $request->getDestinationStopId(),
            "date"             => $request->getDateTime()->format("Y-m-d"),
            "time"             => $request->getDateTime()->format("H:i"),
            "lang"             => $request->getLang(),
            "searchForArrival" => $searchForArrival,
            "format"           => "json",
            "passlist"         => "1",
        ];


        if ($request->getVehicleFilter() > 0) {
            $parameters['products'] = $request->getVehicleFilter();
        }

        if ($request->getOperatorFilter() != null) {
            $parameters['operators'] = join(',', $request->getOperatorFilter());
        }

        if ($request->getViaStopId() != null) {
            $parameters['viaId'] = $request->getViaStopId();
        }

        $response = $this->_webClient->makeRequest(self::TRIPS_ENDPOINT, $parameters);
        $json = json_decode($response->getResponseBody(), true);

        $this->validateResRobotResponse(self::API_NAME_RESROBOT_ROUTEPLANNER, $response, $json);
        return new ResRobotRoutePlanningResponse($response, $json);
    }

    /**
     * @param string                    $key
     * @param StopLocationLookupRequest $request
     *
     * @return StopLocationLookupResponse
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     * @throws RequestTimedOutException
     * @throws ServiceUnavailableException
     */
    public function lookupStopLocation(string $key, StopLocationLookupRequest $request): StopLocationLookupResponse
    {
        $parameters = [
            "accessId" => $key,
            "input"    => $request->getSearchQuery() . '?',
            "maxNo"    => $request->getMaxNumberOfResults(),
            "lang"     => $request->getLanguage(),
            "format"   => "json",
        ];

        $response = $this->_webClient->makeRequest(self::PLATSUPPSLAG_ENDPOINT, $parameters);
        $json = json_decode($response->getResponseBody(), true);

        $this->validateResRobotResponse(self::API_NAME_RESROBOT_FINDSTOPLOCATION, $response, $json);
        return new ResRobotStopLocationLookupResponse($response, $json);
    }

    /**
     * Set the user agent of the application using the SDK.
     *
     * @param string $applicationUserAgent
     */
    public function setApplicationUserAgent(string $applicationUserAgent): void
    {
        $this->applicationUserAgent = $applicationUserAgent;
        $this->_webClient->setUserAgent($this->getUserAgent());
    }

    private function getUserAgent()
    {
        return $this->applicationUserAgent . " VIA " . self::SDK_USER_AGENT;
    }

    /**
     * @param string          $api
     * @param WebResponseImpl $response The response from the server.
     * @param array           $json     The json decoded response.
     *
     * @throws DateTimeOutOfRangeException
     * @throws InvalidKeyException
     * @throws InvalidRequestException
     * @throws InvalidStopLocationException
     * @throws QuotaExceededException
     */
    private function validateResRobotResponse(string $api, WebResponseImpl $response, $json)
    {
        if (key_exists('errorCode', $json)) {
            switch ($json['errorCode']) {
                case 'API_AUTH':
                    throw new InvalidKeyException($response->getRequestParameter('accessId'));
                    break;
                case 'API_QUOTA':
                    throw new QuotaExceededException($api,
                        $response->getRequestParameter('accessId'));
                    break;
                case 'API_PARAM':
                    throw new InvalidRequestException("One or more parameters are invalid",
                        $response->getRequestParameters());
                    break;
                case 'SVC_LOC_NEAR':
                case 'SVC_LOC':
                    throw new InvalidStopLocationException($response->getRequestParameters());
                    break;
                case 'SVC_DATATIME_PERIOD':
                    throw new DateTimeOutOfRangeException($response->getRequestParameters(),
                        $response->getRequestParameter('date'));
                    break;
                default:
                    throw new InvalidRequestException($json['errorText'], $response->getRequestParameters());
                    break;
            }
        }
    }

}