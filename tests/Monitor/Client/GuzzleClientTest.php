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

namespace Hogosha\Monitor\Client;

use GuzzleHttp\Handler\MockHandler;
use Webmozart\Console\IO\BufferedIO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class GuzzleClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BufferedIO
     */
    private $io;

    protected function setUp()
    {
        $this->io = new BufferedIO();
    }

    /**
     * testClient.
     */
    public function testClient()
    {
        $client = GuzzleClient::createClient($this->io);
        $this->assertSame(false, $client->getConfig()['allow_redirects']);
    }

    /**
     * testClientModifiedOptions.
     */
    public function testClientModifiedOptions()
    {
        $client = GuzzleClient::createClient($this->io, ['allow_redirects' => true, 'handler' => new MockHandler()]);
        $this->assertSame(true, $client->getConfig()['allow_redirects']);
        $this->assertInstanceOf(MockHandler::class, $client->getConfig()['handler']);
    }
}
