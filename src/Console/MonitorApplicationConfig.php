<?php

namespace Hogosha\Monitor\Console;

use Hogosha\Monitor\Client\GuzzleClient;
use Hogosha\Monitor\Configuration\ConfigurationDumper;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Console\Handler\InitHandler;
use Hogosha\Monitor\Console\Handler\RunHandler;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Monitor;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\Api\Args\Format\Option;
use Webmozart\Console\Config\DefaultApplicationConfig;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmai.com>
 */
class MonitorApplicationConfig extends DefaultApplicationConfig
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $configurationLoader =  new ConfigurationLoader(
            rtrim(getcwd(), DIRECTORY_SEPARATOR),
            Monitor::CONFIG_FILENAME
        );
        $configurationDumper = new ConfigurationDumper();

        $runner = new Runner(
            new UrlProvider($configurationLoader),
            GuzzleClient::createClient()
        );

        $signature = <<<SIGNATURE
  _____                             _
 |_   _|                           | |
   | |  _ __ ___  _ __   __ _  __ _| |_ ___
   | | | '__/ _ \| '_ \ / _` |/ _` | __/ _ \
  _| |_| | | (_) | | | | (_| | (_| | ||  __/
 |_____|_|  \___/|_| |_|\__, |\__,_|\__\___|
                         __/ |
                        |___/
SIGNATURE;

        $this
            ->setDisplayName($signature."\n".'Irongate Monitor Application')
            ->setName('monitor')
            ->setVersion(Monitor::VERSION)
                ->beginCommand('init')
                    ->setDescription('Create a default configuration file if you do not have one')
                    ->setHandler(new InitHandler($configurationLoader, $configurationDumper))
                    ->setHelp('php <info>bin/monitor</info> init')
                ->end()
                ->beginCommand('run')
                    ->setDescription('Launch the monitor process')
                    ->setHandler(new RunHandler($runner))
                    ->setHelp('php <info>bin/monitor</info> init')
                    ->addOption('format', null, Option::OPTIONAL_VALUE, 'The formatter', 'table')
                ->end()
        ;
    }
}
