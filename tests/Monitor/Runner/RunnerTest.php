<?php

namespace Hogosha\Monitor\Client;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlInfo;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\IO\BufferedIO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunnerTest extends \PHPUnit_Framework_TestCase
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
     * testRunner.
     */
    public function testRunner()
    {
        $urlProvider = new UrlProvider([
            'urls' => [
                'google' => [
                    'url' => 'https://www.google.fr',
                    'method' => 'GET',
                    'headers' => [],
                    'timeout' => 1,
                    'status_code' => 200,
                ],
            ],
        ]);

        $client = GuzzleClient::createClient($this->io, ['handler' => $this->mockClient()]);
        $runner = new Runner($urlProvider, $client);
        $resultCollection = $runner->run();

        $this->assertCount(1, $urlProvider->getUrls());
        $this->assertInstanceOf(UrlInfo::class, $urlProvider->getUrls()['google']);
        $this->assertInstanceOf(ResultCollection::class, $resultCollection);
        $this->assertInstanceOf(Result::class, $resultCollection[0]);
        $this->assertEquals((new Result($this->createUrlInfo(), 200, 0)), $resultCollection[0]);
    }

    /**
     * mockClient.
     *
     * @return MockHandler
     */
    private function mockClient()
    {
        $mock = new MockHandler([
            new Response(200, [], 'test'),
        ]);

        return HandlerStack::create($mock);
    }

    private function createUrlInfo()
    {
        return new UrlInfo(
            'google',
            'https://www.google.fr',
            'GET',
            [],
            1,
            200,
            null,
            null
        );
    }
}
