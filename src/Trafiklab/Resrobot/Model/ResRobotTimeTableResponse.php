<?php


namespace Trafiklab\Resrobot\Contract\Model;


use Trafiklab\Common\Model\Contract\TimeTableResponse;
use Trafiklab\Common\Model\Enum\TimeTableType;
use Trafiklab\Resrobot\Model\ResRobotTimeTableEntry;

class ResRobotTimeTableResponse implements TimeTableResponse
{

    private $_timetable = [];
    private $_type;

    /**
     * Create a ResRobotTimeTableResponse from ResRobots JSON response.
     *
     * @param array $json The API output to parse.
     *
     * @throws \Exception
     */
    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
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
     * @param array $json The API output to parse.
     *
     * @throws \Exception
     */
    private function parseApiResponse(array $json): void
    {
        if (key_exists('errorCode', $json)) {
            throw new \Exception('ResRobot threw an error: ' . $json['errorText'], 500);
        }

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