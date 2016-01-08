<?php

namespace Monitor\Model;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class UrlInfo
{
    protected $name;
    protected $url;
    protected $timeout;

    /**
     * Constructor.
     * @param string  $name
     * @param string  $url
     * @param integer $timeout
     */
    public function __construct($name, $url, $timeout)
    {
        $this->name = $name;
        $this->url = $url;
        $this->timeout = $timeout;
    }

    /**
     * getName.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * getUrl.
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * getTimeOut.
     * @return integer
     */
    public function getTimeOut()
    {
        return $this->timeout;
    }
}
