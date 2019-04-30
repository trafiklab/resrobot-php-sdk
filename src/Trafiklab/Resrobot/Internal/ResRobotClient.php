<?php


namespace Trafiklab\Resrobot\Internal;

use Trafiklab\Common\Internal\CurlWebClient;
use Trafiklab\Common\Internal\WebClient;
use Trafiklab\Common\Model\Contract\RoutePlanningResponse;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Resrobot\Contract\Model\ResRobotTimeTableResponse;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningRequest;
use Trafiklab\ResRobot\Model\ResRobotRoutePlanningResponse;
use Trafiklab\Resrobot\Model\ResRobotTimeTableRequest;


class ResRobotClient
{

    public const DEPARTURES_ENDPOINT = "https://api.resrobot.se/v2/departureBoard";
    public const ARRIVALS_ENDPOINT = "https://api.resrobot.se/v2/arrivalBoard";
    public const TRIPS_ENDPOINT = "https://api.resrobot.se/v2/trip";
    public const SDK_USER_AGENT = "Trafiklab/ResRobot-php-sdk";
    private $applicationUserAgent = "Unknown";
    /**
     * @var WebClient
     */
    private $_webClient;

    public function __construct(WebClient $webClient = null)
    {
        $this->_webClient = $webClient;
        if ($webClient == null) {
            $this->_webClient = new CurlWebClient($this->getUserAgent());
        }
    }


    /**
     * @param string             $key
     * @param ResRobotTimeTableRequest $request
     *
     * @return TimeTableResponse
     * @throws \Exception
     */
    public function getTimeTable(string $key, ResRobotTimeTableRequest $request): TimeTableResponse
    {

        $endpoint = self::DEPARTURES_ENDPOINT;
        if ($request->getTimeTableType() == TimeTableType::ARRIVALS) {
            $endpoint = self::ARRIVALS_ENDPOINT;
        }

        $parameters = [
            "key" => $key,
            "id" => $request->getStopId(),
            "date" => $request->getDateTime()->format("Y-m-d"),
            "time" => $request->getDateTime()->format("H:i"),
            "format" => "json",
            "passlist" => "0",
        ];

        if ($request->getVehicleFilter() > 0) {
            $parameters['products'] = $request->getVehicleFilter();
        }

        if ($request->getOperatorFilter() != null) {
            $parameters['operators'] = join(',', $request->getOperatorFilter());
        }

        $response = $this->_webClient->makeRequest($endpoint, $parameters);
        $json = json_decode($response->getBody(), true);
        return new ResRobotTimeTableResponse($json);
    }

    /**
     * @param string $applicationUserAgent
     */
    public function setApplicationUserAgent(string $applicationUserAgent): void
    {
        $this->applicationUserAgent = $applicationUserAgent;
    }

    /**
     * @param                      $key
     * @param ResRobotRoutePlanningRequest $request
     *
     * @return ResRobotRoutePlanningResponse
     * @throws \Exception
     */
    public function getRoutePlanning($key, ResRobotRoutePlanningRequest $request): ResRobotRoutePlanningResponse
    {
        $parameters = [
            "key" => $key,
            "originId" => $request->getOriginId(),
            "destId" => $request->getDestinationId(),
            "date" => $request->getDateTime()->format("Y-m-d"),
            "time" => $request->getDateTime()->format("H:i"),
            "lang" => $request->getLang(),
            "format" => "json",
            "passlist" => "1",
        ];


        if ($request->getVehicleFilter() > 0) {
            $parameters['products'] = $request->getVehicleFilter();
        }

        if ($request->getOperatorFilter() != null) {
            $parameters['operators'] = join(',', $request->getOperatorFilter());
        }

        if ($request->getViaId() != null) {
            $parameters['viaId'] =  $request->getViaId();
        }

        $response = $this->_webClient->makeRequest(self::TRIPS_ENDPOINT, $parameters);
        $json = json_decode($response->getBody(), true);
        return new ResRobotRoutePlanningResponse($json);
    }


    private function getUserAgent()
    {
        return $this->applicationUserAgent . " VIA " . self::SDK_USER_AGENT;
    }
}