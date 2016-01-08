<?php

namespace Monitor\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class GuzzleClient
{
    /**
     * createClient.
     * @return Client
     */
    public static function createClient()
    {
        return new Client([
            'handler' => new CurlMultiHandler(),
            'allow_redirects' => false,
        ]);
    }
}
