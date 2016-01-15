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

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testRunner.
     */
    public function testRunner()
    {
        $configurationLoaderMock = $this->prophesize(ConfigurationLoader::class);
        $configurationLoaderMock
        ->loadConfiguration()
        ->shouldBeCalled()
        ->willReturn(
            [
                'urls' => [
                    'google' => [
                        'url' => 'https://www.google.fr',
                        'timeout' => 1,
                        'status_code' => 200,
                        'service_uid' => '',
                    ],
                ],
            ]
        );

        $urlProvider = new UrlProvider($configurationLoaderMock->reveal());

        $client = GuzzleClient::createClient(['handler' => $this->mockClient()]);
        $runner = new Runner($urlProvider, $client);
        $resultCollection = $runner->run();

        $this->assertCount(1, $urlProvider->getUrls());
        $this->assertInstanceOf(UrlInfo::class, $urlProvider->getUrls()['google']);
        $this->assertInstanceOf(ResultCollection::class, $resultCollection);
        $this->assertInstanceOf(Result::class, $resultCollection[0]);
        $this->assertEquals((new Result('google', 200, 0, 200)), $resultCollection[0]);
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
}
