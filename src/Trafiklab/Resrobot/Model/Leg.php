<?php


namespace Trafiklab\ResRobot\Model;


class Leg
{

    private $_destination;
    private $_direction;
    private $_intermediaryStops;
    private $_notes;
    private $_origin;
    private $_type;
    private $_vehicle;

    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * @return Stop
     */
    public function getOrigin(): Stop
    {
        return $this->_origin;
    }

    /**
     * @return Stop
     */
    public function getDestination(): Stop
    {
        return $this->_destination;
    }

    /**
     * @return string[]
     */
    public function getNotes(): array
    {
        return $this->_notes;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->_vehicle;
    }

    /**
     * @return Stop[]
     */
    public function getIntermediaryStops(): array
    {
        return $this->_intermediaryStops;
    }

    /**
     * @return string
     */
    public function getDirection(): ?string
    {
        return $this->_direction;
    }

    /**
     * JNY: journey, WALK: walking.
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    private function parseApiResponse(array $json)
    {
        $this->_notes = [];
        if (key_exists('Notes', $json)) {
            foreach ($json['Notes']['Note'] as $note) {
                $this->_notes[] = $note['value'];
            }
        }

        $this->_type = $json['type'];

        if ($this->_type == "JNY") {
            $this->_vehicle = new Vehicle($json['Product']);

            $this->_intermediaryStops = [];
            foreach ($json['Stops']['Stop'] as $stop) {
                $this->_intermediaryStops[] = new Stop($stop);
            }
            // The stops array also includes the departure and arrival. Pop them of and use them instead of the
            // origin and destination data delivered by the API. This approach uses only one data type instead
            // of 2, making it easier to handle for end users.
            $this->_origin = array_shift($this->_intermediaryStops);
            $this->_destination = array_pop($this->_intermediaryStops);

            $this->_direction = $json['direction'];
        } else {
            // Should Dist (distance) and Duration (time) be parsed in case of a walk?

            $this->_origin = new Stop($json['Origin']);
            $this->_destination = new Stop($json['Destination']);
        }

    }
}