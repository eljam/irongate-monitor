<?php

/*
 * This file is part of the hogosha-monitor package
 *
 * Copyright (c) 2016 Guillaume Cavana
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */

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
