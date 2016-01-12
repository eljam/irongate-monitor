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
use Symfony\Component\Filesystem\Filesystem;
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

        $configurationLoader =  new ConfigurationLoader();
        $configurationDumper = new ConfigurationDumper();

        $filesystem = new Filesystem();

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
                ->addOption('config', 'c', Option::OPTIONAL_VALUE, 'Config file path', rtrim(getcwd(), DIRECTORY_SEPARATOR))
                ->beginCommand('init')
                    ->setDescription('Create a default configuration file if you do not have one')
                    ->setHandler(function () use ($configurationLoader, $configurationDumper, $filesystem) {
                        return new InitHandler($configurationLoader, $configurationDumper, $filesystem);
                    })
                    ->setHelp('php <info>bin/monitor</info> init')
                ->end()
                ->beginCommand('run')
                    ->setDescription('Launch the monitor process')
                    ->setHandler(function () use ($configurationLoader) {
                        return new RunHandler($configurationLoader);
                    })
                    ->setHelp('php <info>bin/monitor</info> init')
                    ->addOption('format', null, Option::OPTIONAL_VALUE, 'The formatter', 'table')
                ->end()
        ;
    }
}
