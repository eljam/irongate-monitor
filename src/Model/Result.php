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

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $statusCode
     * @param float  $responseTime
     */
    public function __construct($name, $statusCode, $responseTime)
    {
        $this->name = $name;
        $this->statusCode = $statusCode;
        $this->responseTime = $responseTime;
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
     * getReponseTime.
     *
     * @return float
     */
    public function getReponseTime()
    {
        return $this->responseTime;
    }
}
