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

namespace Hogosha\Monitor\Pusher;

use Hogosha\Monitor\Model\Result;
use Hogosha\Sdk\Api\Client;

/**
 * Class AbstractPusher.
 */
abstract class AbstractPusher implements PusherInterface
{
    /**
     * $client.
     *
     * @var Client
     */
    protected $client;

    /**
     * $options.
     *
     * @var array
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * getDefaultFailedIncidentMessage.
     *
     * @return string Default message used to create a incident when a failure as occured
     */
    public function getDefaultFailedIncidentMessage()
    {
        return $this->options['default_failed_incident_message'];
    }

    /**
     * getDefaultResolvedIncidentMessage.
     *
     * @return string Default message used to create a incident when a service is resolved as occured
     */
    public function getDefaultResolvedIncidentMessage()
    {
        return $this->options['default_resolved_incident_message'];
    }

    /**
     * getClient.
     *
     * @return Client
     */
    public function getClient()
    {
        return new Client(
            [
                'username' => $this->options['username'],
                'password' => $this->options['password'],
                'base_uri' => $this->options['base_uri'],
            ]
        );
    }

    /**
     * push.
     *
     * @param Result $result
     *
     * @return mixed
     */
    abstract public function push(Result $result);
}
