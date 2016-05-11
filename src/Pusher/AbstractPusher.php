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

use Concat\Http\Middleware\Logger;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use Hogosha\Monitor\Model\Result;
use Hogosha\Sdk\Api\Client as SdkClient;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Webmozart\Console\Adapter\IOOutput;
use Webmozart\Console\Api\IO\IO;

/**
 * Class AbstractPusher.
 */
abstract class AbstractPusher implements PusherInterface
{
    /**
     * $client.
     *
     * @var SdkClient
     */
    protected $client;

    /**
     * $options.
     *
     * @var array
     */
    protected $options;

    /**
     * $io.
     *
     * @var IO
     */
    protected $io;

    /**
     * Constructor.
     *
     * @param array  $options
     * @param IO     $io
     * @param Client $client
     */
    public function __construct(array $options, IO $io, SdkClient $client = null)
    {
        $this->io = $io;
        $this->options = $options;
        $this->client = null !== $client ? $client : $this->createClient();
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
     * @return SdkClient
     */
    protected function createClient()
    {
        $stack = HandlerStack::create();

        $middleware = (new Logger((new ConsoleLogger(new IOOutput($this->io)))));
        $middleware->setRequestLoggingEnabled(true);
        $format = "<fg=white;bg=blue>Request:</>\n <fg=white;bg=default>{host} {req_headers} \nBody: {req_body} </>\n <fg=white;bg=blue>Reponse:</>\n<fg=white;bg=default>{res_body} {res_headers}</>\n";
        $middleware->setFormatter((new MessageFormatter($format)));

        $stack->push($middleware, 'logger');

        return new SdkClient(
            [
                'username' => $this->options['username'],
                'password' => $this->options['password'],
                'base_uri' => $this->options['base_uri'],
                'handler' => $stack,
            ]
        );
    }

    /**
     * push.
     *
     * @param Result $result
     *
     * @throws \Exception
     *
     * @return mixed
     */
    abstract public function push(Result $result);
}
