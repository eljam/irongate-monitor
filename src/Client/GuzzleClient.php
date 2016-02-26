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
