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

namespace Hogosha\Monitor\Console\Handler;

use Hogosha\Monitor\Pusher\PusherManager;
use Hogosha\Monitor\Client\GuzzleClient;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Renderer\RendererFactory;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunHandler
{
    /**
     * $configurationLoader.
     *
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * Constructor.
     *
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * handle.
     *
     * @param Args $args
     * @param IO   $io
     *
     * @return int
     */
    public function handle(Args $args, IO $io)
    {
        $this->configurationLoader->setRootDirectory($args->getOption('config'));

        $config = $this->configurationLoader->loadConfiguration();

        try {
            $urlProvider = new UrlProvider($config);
            $runner = new Runner(
                $urlProvider,
                GuzzleClient::createClient($io)
            );

            $renderer = RendererFactory::create($args->getOption('format'), $io);
            $results = $runner->run();
            $renderer->render($results);

            if (isset($config['hogosha_portal']['metric_update']) ||
                isset($config['hogosha_portal']['incident_update'])
            ) {
                $pusher = new PusherManager($config, $io);
                $pusher->push($results);
            }
        } catch (ConfigurationLoadingException $e) {
            $io->writeLine(
                sprintf(
                    '<info>There is no configuration file in</info> "%s"',
                    $this->configurationLoader->getRootDirectory()
                )
            );
        }
    }
}
