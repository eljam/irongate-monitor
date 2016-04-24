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

use Concat\Http\Middleware\Logger;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Webmozart\Console\Adapter\IOOutput;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class GuzzleClient
{
    /**
     * createClient.
     *
     * @param IO    $io
     * @param array $options
     *
     * @return Client
     */
    public static function createClient(IO $io, $options = [])
    {
        $stack = HandlerStack::create();

        $middleware = (new Logger((new ConsoleLogger(new IOOutput($io)))));
        $middleware->setRequestLoggingEnabled(true);
        $format = "<fg=white;bg=blue>Request:</>\n <fg=white;bg=default>{host} {req_headers} \nBody: {req_body} </>\n <fg=white;bg=blue>Reponse:</>\n<fg=white;bg=default>{res_body} {res_headers}</>\n";
        $middleware->setFormatter((new MessageFormatter($format)));

        $stack->push($middleware, 'logger');

        $defaults = [
            'handler' => $stack,
            'allow_redirects' => false,
        ];

        $options = array_merge($defaults, $options);

        return new Client($options);
    }
}
