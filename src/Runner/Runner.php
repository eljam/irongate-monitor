<?php

namespace Hogosha\Monitor\Runner;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
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
        $promises = [];

        $stats = [];
        foreach ($this->urlProvider->getUrls() as $url) {
            $promises[$url->getName()] = $this->client->getAsync(
                $url->getUrl(),
                [
                    'timeout' => $url->getTimeout(),
                    'on_stats' => function (TransferStats $tranferStats) use (&$stats, $url) {
                        $stats[$url->getName()]['total_time'] = $tranferStats->getTransferTime();
                    },
                ]
            );
        }

        $results = Promise\unwrap($promises);

        return $this->createResultCollection($results, $stats);
    }

    /**
     * createResultCollection.
     *
     * @param array $results
     * @param array $stats
     *
     * @return ResultCollection
     */
    private function createResultCollection(array $results, array $stats)
    {
        $resultCollection = new ResultCollection();
        foreach ($results as $name => $result) {
            $resultCollection->append((new Result($name, $result->getStatusCode(), $stats[$name]['total_time'])));
        }

        return $resultCollection;
    }
}
