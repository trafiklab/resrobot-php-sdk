<?php


namespace Trafiklab\ResRobot\Model;


use Trafiklab\Common\Model\Contract\RoutePlanningRequest;
use Trafiklab\Common\Model\Enum\RoutePlanningSearchType;

class ResRobotRoutePlanningRequest extends ResRobotBaseRequest implements RoutePlanningRequest
{

    private $_originId;
    private $_destinationId;
    private $_viaId;
    private $_lang = "sv";
    private $_routePlanningType = RoutePlanningSearchType::DEPART_AT_SPECIFIED_TIME;

    /**
     * @return string
     */
    public function getOriginStopId(): string
    {
        return $this->_originId;
    }

    /**
     * @param string $originId
     */
    public function setOriginStopId(string $originId): void
    {
        $this->_originId = $originId;
    }

    /**
     * @return string
     */
    public function getDestinationStopId(): string
    {
        return $this->_destinationId;
    }

    /**
     * @param string $destinationId
     */
    public function setDestinationStopId(string $destinationId): void
    {
        $this->_destinationId = $destinationId;
    }

    /**
     * @return string
     */
    public function getViaStopId(): ?string
    {
        return $this->_viaId;
    }

    /**
     * @param string $viaId
     */
    public function setViaStopId(?string $viaId): void
    {
        $this->_viaId = $viaId;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->_lang;
    }

    /**
     * @param string $lang
     */
    public function setLang(string $lang): void
    {
        $this->_lang = $lang;
    }

    public function getRoutePlanningSearchType(): int
    {
        return $this->_routePlanningType;
    }

    public function setRoutePlanningSearchType(int $timeTableType): void
    {
        $this->_routePlanningType = $timeTableType;
    }
}