<?php


namespace Trafiklab\Resrobot\Internal;

use Trafiklab\Resrobot\Model\TimeTableResponse;

class ResRobotClient
{

    private const DEPARTURES_ENDPOINT = "https://api.resrobot.se/v2/departureBoard";
    private const ARRIVALS_ENDPOINT = "https://api.resrobot.se/v2/arrivalBoard";
    private const TRIPS_ENDPOINT = "https://api.resrobot.se/v2/trip";
    private const SDK_USER_AGENT = "Trafiklab/ResRobot-php-sdk";
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
     * @param string         $key
     * @param string         $stopId
     * @param \DateTime|null $dateTime
     * @param int            $productFilter
     * @param array|null     $operatorFilter
     *
     * @return TimeTableResponse
     * @throws \Exception
     */
    public function getDepartures(string $key, string $stopId, \DateTime $dateTime = null,
                                  int $productFilter = -1, array $operatorFilter = null): TimeTableResponse
    {
        if ($dateTime == null) {
            $dateTime = new \DateTime();
        }
        return $this->getTimeTable(self::DEPARTURES_ENDPOINT, $key, $stopId, $dateTime,
            $productFilter, $operatorFilter);
    }

    /**
     * @param string         $key
     * @param string         $stopId
     * @param \DateTime|null $dateTime
     * @param int            $productFilter
     * @param array|null     $operatorFilter
     *
     * @return TimeTableResponse
     * @throws \Exception
     */
    public function getArrivals(string $key, string $stopId, \DateTime $dateTime = null,
                                int $productFilter = -1, array $operatorFilter = null): TimeTableResponse
    {
        if ($dateTime == null) {
            $dateTime = new \DateTime();
        }
        return $this->getTimeTable(self::ARRIVALS_ENDPOINT, $key, $stopId, $dateTime,
            $productFilter, $operatorFilter);
    }

    /**
     * @param string $applicationUserAgent
     */
    public function setApplicationUserAgent(string $applicationUserAgent): void
    {
        $this->applicationUserAgent = $applicationUserAgent;
    }

    /**
     * @param string     $endpoint
     * @param string     $key
     * @param string     $stopId
     * @param \DateTime  $dateTime
     * @param int        $productFilter
     * @param array|null $operatorFilter
     *
     * @return TimeTableResponse
     * @throws \Exception
     */
    private function getTimeTable(string $endpoint, string $key, string $stopId, \DateTime $dateTime,
                                  int $productFilter = -1, array $operatorFilter = null): TimeTableResponse
    {

        $parameters = [
            "key" => $key,
            "id" => $stopId,
            "date" => $dateTime->format("Y-m-d"),
            "time" => $dateTime->format("H:i"),
            "format" => "json",
            "passlist" => "0",
        ];

        if ($productFilter > 0) {
            $parameters['productFilter'] = $productFilter;
        }

        if ($operatorFilter != null) {
            $parameters['$operatorFilter'] = join(',', $operatorFilter);
        }

        $response = $this->_webClient->makeRequest($endpoint, $parameters);
        $json = json_decode($response, true);
        return new TimeTableResponse($json);
    }


    private function getUserAgent()
    {
        return $this->applicationUserAgent . " VIA " . self::SDK_USER_AGENT;
    }
}