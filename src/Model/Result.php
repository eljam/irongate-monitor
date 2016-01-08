<?php

namespace Monitor\Model;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class Result
{
    protected $name;
    protected $statusCode;

    /**
     * Constructor.
     * @param string $name
     * @param string $statusCode
     */
    public function __construct($name, $statusCode)
    {
        $this->name = $name;
        $this->statusCode = $statusCode;
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
     * getStatusCode.
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
