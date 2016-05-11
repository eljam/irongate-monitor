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

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\UrlInfo;
use Hogosha\Monitor\Validator\Validator;
use Hogosha\Sdk\Api\Client as SdkClient;
use Webmozart\Console\IO\BufferedIO;

/**
 * MetricPusherTest.
 */
class MetricPusherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * pushData.
     */
    public function testPushData()
    {
        $mock = new MockHandler(
            [
                new Response(200), //Jwt response
                new Response(500), //metric api call response
            ]
        );
        $handler = HandlerStack::create($mock);

        $options =
            [
                'username' => null,
                'password' => null,
                'base_uri' => null,
                'default_resolved_incident_message' => null,
                'default_failed_incident_message' => null,
                'metric_update' => null,
                'incident_update' => null,
                'handler' => $handler,
            ];

        $this->setExpectedException(
            'GuzzleHttp\Exception\BadResponseException'
        );

        $pusher = new MetricPusher($options, (new BufferedIO()), (new SdkClient(
            [
                'username' => null,
                'password' => null,
                'base_uri' => '/',
                'handler' => $handler,
            ]
        )));
        $pusher->push((new Result($this->createUrlInfo(), 200, 0.200, null, null)));
    }

    private function createUrlInfo($validator = true)
    {
        return new UrlInfo(
            'google',
            'https://www.google.fr',
            'GET',
            [],
            1,
            200,
            (new Validator([])),
            null,
            null
        );
    }
}
