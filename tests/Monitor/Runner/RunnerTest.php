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

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlInfo;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Runner\Runner;
use Hogosha\Monitor\Validator\Validator;
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
    public function testRunnerWithValidator()
    {
        $urlProvider = new UrlProvider([
            'urls' => [
                'google' => [
                    'url' => 'https://www.google.fr',
                    'method' => 'GET',
                    'headers' => [],
                    'timeout' => 1,
                    'validator' => ['type' => 'html', 'match' => '/test/'],
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
        $this->assertInstanceOf(Result::class, $resultCollection['google']);
        $this->assertEquals((new Result($this->createUrlInfo(), 200, 0, null, true)), $resultCollection['google']);
    }

    public function testRunnerWithoutValidator()
    {
        $urlProvider = new UrlProvider([
            'urls' => [
                'google' => [
                    'url' => 'https://www.google.fr',
                    'method' => 'GET',
                    'headers' => [],
                    'timeout' => 1,
                    'validator' => [],
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
        $this->assertInstanceOf(Result::class, $resultCollection['google']);
        $this->assertEquals((new Result($this->createUrlInfo(false), 200, 0, null, null)), $resultCollection['google']);
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

    private function createUrlInfo($validator = true)
    {
        return new UrlInfo(
            'google',
            'https://www.google.fr',
            'GET',
            [],
            1,
            200,
            $validator ?
            (new Validator(['type' => 'html', 'match' => '/test/'])) :
            (new Validator()),
            null,
            null
        );
    }
}
