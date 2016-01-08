<?php

namespace Monitor\Runner;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Monitor\Client\GuzzleClient;
use Monitor\Model\Result;
use Monitor\Model\ResultCollection;
use Monitor\Model\UrlProvider;

/**
* @author Guillaume Cavana <guillaume.cavana@gmail.com>
*/
class Runner
{
    protected $urlProvider;

    protected $client;

    /**
     * Constructor.
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
     * @return array
     */
    public function run()
    {
        $promises = [];

        foreach ($this->urlProvider->getUrls() as $url) {
             $promises[$url->getName()] = $this->client->getAsync($url->getUrl());
        }

        $results = Promise\unwrap($promises);

        return $this->createResultCollection($results);
    }

    /**
     * createResultCollection.
     * @param array $results
     * @return ResultCollection
     */
    private function createResultCollection(array $results)
    {
        $resultCollection = new ResultCollection();
        foreach ($results as $name => $result) {
            $resultCollection->append((new Result($name, $result->getStatusCode())));
        }

        return $resultCollection;
    }
}
