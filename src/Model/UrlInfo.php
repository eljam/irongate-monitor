<?php

namespace Hogosha\Monitor\Model;

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

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $url
     * @param string $method
     * @param array  $headers
     * @param int    $timeout
     * @param int    $expectedStatus
     */
    public function __construct(
        $name,
        $url,
        $method,
        array $headers,
        $timeout,
        $expectedStatus
    ) {
        $this->name = $name;
        $this->url = $url;
        $this->method = $method;
        $this->headers = $headers;
        $this->timeout = $timeout;
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
}
