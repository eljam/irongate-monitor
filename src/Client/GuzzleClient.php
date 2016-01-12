<?php

namespace Hogosha\Monitor\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class GuzzleClient
{
    /**
     * createClient.
     *
     * @param array $options
     *
     * @return Client
     */
    public static function createClient($options = [])
    {
        $defaults = [
            'handler' => new CurlMultiHandler(),
            'allow_redirects' => false,
        ];

        $options = array_merge($defaults, $options);

        return new Client($options);
    }
}
