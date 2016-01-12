<?php

namespace Hogosha\Monitor\Client;

use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\Handler\MockHandler;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class GuzzleClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testClient.
     */
    public function testClient()
    {
        $client = GuzzleClient::createClient();
        $this->assertSame(false, $client->getConfig()['allow_redirects']);
        $this->assertInstanceOf(CurlMultiHandler::class, $client->getConfig()['handler']);
    }

    /**
     * testClientModifiedOptions.
     */
    public function testClientModifiedOptions()
    {
        $client = GuzzleClient::createClient(['allow_redirects' => true, 'handler' => new MockHandler()]);
        $this->assertSame(true, $client->getConfig()['allow_redirects']);
        $this->assertInstanceOf(MockHandler::class, $client->getConfig()['handler']);
    }
}
