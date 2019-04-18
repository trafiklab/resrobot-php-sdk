<?php


namespace Trafiklab\ResRobot\Model;


class Vehicle
{

    private $_name;
    private $_number;
    private $_type;
    private $_operatorCode;
    private $_operatorName;
    private $_operatorUrl;

    /**
     * Product constructor.
     *
     * @param array $json
     */
    public function __construct(array $json)
    {
        $this->parseApiResponse($json);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->_number;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return mixed
     */
    public function getOperatorCode()
    {
        return $this->_operatorCode;
    }

    /**
     * @return mixed
     */
    public function getOperatorUrl()
    {
        return $this->_operatorUrl;
    }

    /**
     * @return mixed
     */
    public function getOperatorName()
    {
        return $this->_operatorName;
    }

    private function parseApiResponse(array $json)
    {
        $this->_name = $json['name'];
        $this->_number = $json['num'];
        $this->_type = $json['catOutL'];
        $this->_operatorCode = $json['operatorCode'];
        $this->_operatorName = $json['operator'];
        $this->_operatorUrl = $json['operatorUrl'];
    }
}
