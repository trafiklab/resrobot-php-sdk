<?php


namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Internal\WebResponseImpl;
use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Contract\WebResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;

class ResRobotTimeTableResponse implements TimeTableResponse
{

    private $_timetable = [];
    private $_type;
    private $_response;

    /**
     * Create a ResRobotTimeTableResponse from ResRobots JSON response.
     *
     * @param WebResponse $response
     * @param array       $json The API output to parse.
     *
     * @internal
     */
    public function __construct(WebResponse $response, array $json)
    {
        $this->parseApiResponse($json);
        $this->_response = $response;
    }

    /**
     * @return ResRobotTimeTableEntry[] The requested timetable as an array of timetable entries.
     */
    public function getTimetable(): array
    {
        return $this->_timetable;
    }

    /**
     * @return int The type of the stops in this timetable.
     */
    public function getType(): int
    {
        return $this->_type;
    }

    /**
     * Get the original response from the API.
     *
     * @return WebResponseImpl
     */
    public function getOriginalApiResponse(): WebResponse
    {
        return $this->_response;
    }

    /**
     * @param array $json The API output to parse.
     */
    private function parseApiResponse(array $json): void
    {

        if (key_exists('Departure', $json)) {
            $responseRoot = 'Departure';
            $this->_type = TimeTableType::DEPARTURES;
        } else if (key_exists('Arrival', $json)) {
            $responseRoot = 'Arrival';
            $this->_type = TimeTableType::ARRIVALS;
        } else {
            // When there are no results, ResRobot just returns "{ }" without any information.
            $this->_timetable = [];
            // No further parsing is required
            return;
        }

        foreach ($json[$responseRoot] as $key => $entry) {
            $this->_timetable[] = new ResRobotTimeTableEntry($entry, $this->getType());
        }
    }
}