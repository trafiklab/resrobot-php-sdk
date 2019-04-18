<?php


namespace Trafiklab\Resrobot\Model;


class TimeTableResponse
{

    private $_timetable = [];
    private $_type;

    /**
     * Create a TimeTableResponse from ResRobots JSON response.
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
     * @return TimeTableEntry[] The requested timetable as an array of timetable entries.
     */
    public function getTimetable(): array
    {
        return $this->_timetable;
    }

    /**
     * @return TimeTableType The type of the stops in this timetable.
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
            throw new \Exception('Invalid API response received!');
        }


        foreach ($json[$responseRoot] as $key => $entry) {
            $this->_timetable[] = new TimeTableEntry($entry, $this->getType());
        }
    }


}