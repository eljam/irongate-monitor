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

use Hogosha\Monitor\Validator\Validator;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class UrlInfo
{
    protected $name;
    protected $url;
    protected $method;
    protected $headers;
    protected $timeout;
    protected $expectedStatus;
    protected $validator;
    protected $metricUuid;
    protected $serviceUuid;

    /**
     * Constructor.
     *
     * @param string    $name
     * @param string    $url
     * @param string    $method
     * @param array     $headers
     * @param int       $timeout
     * @param int       $expectedStatus
     * @param Validator $validator
     * @param string    $metricUuid
     * @param string    $serviceUuid
     */
    public function __construct(
        $name,
        $url,
        $method,
        array $headers,
        $timeout,
        $expectedStatus,
        Validator $validator,
        $metricUuid,
        $serviceUuid
    ) {
        $this->name = $name;
        $this->url = $url;
        $this->method = $method;
        $this->headers = $headers;
        $this->timeout = $timeout;
        $this->expectedStatus = $expectedStatus;
        $this->metricUuid = $metricUuid;
        $this->serviceUuid = $serviceUuid;
        $this->validator = $validator;
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
     * getUrl.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * getMethod.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * getHeaders.
     *
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * getTimeOut.
     *
     * @return int
     */
    public function getTimeOut()
    {
        return $this->timeout;
    }

    /**
     * getTimeOut.
     *
     * @return int
     */
    public function getExpectedStatus()
    {
        return $this->expectedStatus;
    }

    /**
     * getMetricUuid.
     *
     * @return string
     */
    public function getMetricUuid()
    {
        return $this->metricUuid;
    }

    /**
     * getServiceUuid.
     *
     * @return string
     */
    public function getServiceUuid()
    {
        return $this->serviceUuid;
    }

    /**
     * getValidator.
     *
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
