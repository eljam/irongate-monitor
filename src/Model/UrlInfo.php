<?php

namespace Hogosha\Monitor\Model;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class UrlInfo
{
    protected $name;
    protected $url;
    protected $timeout;
    protected $expectedStatus;

    /**
     * Constructor.
     *
     * @param string $name
     * @param string $url
     * @param int    $timeout
     * @param int    $expectedStatus
     */
    public function __construct($name, $url, $timeout, $expectedStatus)
    {
        $this->name = $name;
        $this->url = $url;
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
