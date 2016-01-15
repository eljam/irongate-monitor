<?php

namespace Hogosha\Monitor\Model;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class Result
{
    protected $name;
    protected $statusCode;
    protected $responseTime;
    protected $expectedStatus;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $statusCode
     * @param float  $responseTime
     * @param int    $expectedStatus
     */
    public function __construct($name, $statusCode, $responseTime, $expectedStatus)
    {
        $this->name = $name;
        $this->statusCode = $statusCode;
        $this->responseTime = $responseTime;
        $this->expectedStatus = $expectedStatus;
    }

    /**
     * getName.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getStatusCode.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * getExpectedStatus.
     *
     * @return int
     */
    public function getExpectedStatus()
    {
        return $this->expectedStatus;
    }

    /**
     * getReponseTime.
     *
     * @return float
     */
    public function getReponseTime()
    {
        return $this->responseTime;
    }
}
