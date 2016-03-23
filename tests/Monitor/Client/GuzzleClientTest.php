<?php

namespace Hogosha\Monitor\Client;

use GuzzleHttp\Handler\CurlMultiHandler;
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
