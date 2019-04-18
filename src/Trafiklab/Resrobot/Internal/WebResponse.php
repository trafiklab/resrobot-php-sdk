<?php


namespace Trafiklab\Resrobot\Internal;


class WebResponse
{
    private $_responseCode;
    private $_body;

    public function __construct(int $responseCode,  $body)
    {
        $this->_responseCode = $responseCode;
        $this->_body = $body;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * @return int
     */
    public function getResponseCode(): int
    {
        return $this->_responseCode;
    }


}