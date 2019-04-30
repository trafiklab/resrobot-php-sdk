<?php


namespace Trafiklab\ResRobot\Model;


class ResRobotRoutePlanningRequest extends ResRobotBaseRequest
{

    private $_originId;
    private $_destinationId;
    private $_viaId;
    private $_lang = "sv";

    /**
     * @return string
     */
    public function getOriginId(): string
    {
        return $this->_originId;
    }

    /**
     * @param string $originId
     */
    public function setOriginId(string $originId): void
    {
        $this->_originId = $originId;
    }

    /**
     * @return string
     */
    public function getDestinationId(): string
    {
        return $this->_destinationId;
    }

    /**
     * @param string $destinationId
     */
    public function setDestinationId(string $destinationId): void
    {
        $this->_destinationId = $destinationId;
    }

    /**
     * @return string
     */
    public function getViaId(): ?string
    {
        return $this->_viaId;
    }

    /**
     * @param string $viaId
     */
    public function setViaId(string $viaId): void
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


}