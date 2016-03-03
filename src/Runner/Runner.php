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

namespace Hogosha\Monitor\Runner;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Pool;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\TransferStats;
use Hogosha\Monitor\Client\GuzzleClient;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlProvider;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class Runner
{
    protected $urlProvider;

    protected $client;

    /**
     * Constructor.
     *
     * @param UrlProvider  $urlProvider
     * @param GuzzleClient $client
     */
    public function __construct(UrlProvider $urlProvider, Client $client)
    {
        $this->urlProvider = $urlProvider;
        $this->client = $client;
    }

    /**
     * run.
     *
     * @return array
     */
    public function run()
    {
        $stats = [];

        $urls = $this->urlProvider->getUrls();
        $client = $this->client;

        $resultCollection = new ResultCollection();

        $requests = function () use ($urls, $client, $resultCollection) {
            foreach ($urls as $url) {
                yield function () use ($client, $url, $resultCollection) {
                    return $client->sendAsync(
                        new Request(
                            $url->getMethod(),
                            $url->getUrl(),
                            $url->getHeaders()
                        ),
                        [
                            'timeout' => $url->getTimeout(),
                            'on_stats' => function (TransferStats $tranferStats) use ($url, $resultCollection) {

                                if ($tranferStats->hasResponse()) {
                                    $statusCode = $tranferStats->getResponse()->getStatusCode();
                                    $transferTime = $tranferStats->getTransferTime();
                                } else {
                                    // If we have a connection error
                                    $statusCode = 400;
                                    $transferTime = 0;
                                }

                                $resultCollection->append(
                                    (new Result(
                                        $url,
                                        $statusCode,
                                        $transferTime
                                    ))
                                );
                            },
                        ]
                    );
                };
            }
        };

        $pool = new Pool($this->client, $requests(), [
            'concurrency' => 5,
        ]);

        $promise = $pool->promise();

        $promise->wait();

        return $resultCollection;
    }
}
