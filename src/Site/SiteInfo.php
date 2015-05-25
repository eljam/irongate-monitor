<?php

namespace Irongate\Monitor\Site;

class SiteInfo
{
    protected $name;
    protected $url;
    protected $timeout;

    public function __construct($name, $url, $timeout)
    {
        $this->name = $name;
        $this->url = $url;
        $this->timeout = $timeout;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getTimeOut()
    {
        return $this->timeout;
    }
}
