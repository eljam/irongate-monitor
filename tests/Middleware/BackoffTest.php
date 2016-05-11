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

namespace Hogosha\Monitor\Middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class BackoffTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testRetriesConnectException.
     */
    public function testRetriesConnectException()
    {
        $mock = new MockHandler(
            [
                new ConnectException('Error 1', new Request('GET', 'test')),
                new Response(200, ['X-Foo' => 'Bar']),
            ]
        );
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::retry(Backoff::decider(), Backoff::delay()));
        $client = new Client(['handler' => $handler]);

        $this->assertEquals(200, $client->request('GET', '/')->getStatusCode());
    }

    /**
     * testRetryLimit.
     */
    public function testRetryLimit()
    {
        $mock = new MockHandler(
            [
                new ConnectException('Error 1', new Request('GET', 'test')),
                new ConnectException('Error 2', new Request('GET', 'test')),
                new ConnectException('Error 3', new Request('GET', 'test')),
                new ConnectException('Error 4', new Request('GET', 'test')),
            ]
        );
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::retry(Backoff::decider(), Backoff::delay()));
        $client = new Client(['handler' => $handler]);
        $this->setExpectedException(
            'GuzzleHttp\Exception\ConnectException',
            'Error 4'
        );
        $client->request('GET', '/')->getStatusCode();
    }

    /**
     * testRetryDelay.
     */
    public function testRetryDelay()
    {
        $mock = new MockHandler(
            [
                new ConnectException('+1 second delay', new Request('GET', 'test')),
                new ConnectException('+2 second delay', new Request('GET', 'test')),
                new Response(200),
            ]
        );
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::retry(Backoff::decider(), Backoff::delay()));
        $client = new Client(['handler' => $handler]);
        $startTime = time();
        $client->request('GET', '/')->getStatusCode();
        $endTime = time();
        $this->assertGreaterThan($startTime + 2, $endTime);
    }

    /**
     * testRetries500Errors.
     */
    public function testRetries500Errors()
    {
        $mock = new MockHandler(
            [
                new Response(500),
                new Response(200),
            ]
        );
        $handler = HandlerStack::create($mock);
        $handler->push(Middleware::retry(Backoff::decider(), Backoff::delay()));
        $client = new Client(['handler' => $handler]);
        $this->assertEquals(200, $client->request('GET', '/')->getStatusCode());
    }
}
