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

namespace Hogosha\Monitor\Console;

use Hogosha\Monitor\Configuration\ConfigurationDumper;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Console\Handler\InitHandler;
use Hogosha\Monitor\Console\Handler\RunHandler;
use Hogosha\Monitor\Monitor;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Console\Api\Args\Format\Option;
use Webmozart\Console\Config\DefaultApplicationConfig;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class MonitorApplicationConfig extends DefaultApplicationConfig
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $configurationLoader = new ConfigurationLoader();
        $configurationDumper = new ConfigurationDumper();

        $filesystem = new Filesystem();

        $signature = <<<SIGNATURE
  _   _   ___    ____   ___   ____   _   _     _
 | | | | / _ \  / ___| / _ \ / ___| | | | |   / \
 | |_| || | | || |  _ | | | |\___ \ | |_| |  / _ \
 |  _  || |_| || |_| || |_| | ___) ||  _  | / ___ \
 |_| |_| \___/  \____| \___/ |____/ |_| |_|/_/   \_\

SIGNATURE;

        $this
            ->setDisplayName($signature."\n".'Hogosha Monitoring Tool')
            ->setName('monitor')
            ->setVersion(Monitor::VERSION)
                ->addOption('config', 'c', Option::OPTIONAL_VALUE, 'Config file path', rtrim(getcwd(), DIRECTORY_SEPARATOR))
                ->beginCommand('init')
                    ->setDescription('Create a default configuration file if you do not have one')
                    ->setHandler(function () use ($configurationLoader, $configurationDumper, $filesystem) {
                        return new InitHandler($configurationLoader, $configurationDumper, $filesystem);
                    })
                    ->setHelp('php <info>bin/monitor</info> init')
                    ->addOption('force', 'f', Option::OPTIONAL_VALUE, 'Overwrite the config file')
                ->end()
                ->beginCommand('run')
                    ->setDescription('Launch the monitor process')
                    ->setHandler(function () use ($configurationLoader) {
                        return new RunHandler($configurationLoader);
                    })
                    ->setHelp('php <info>bin/monitor</info> run')
                    ->addOption('format', 'f', Option::OPTIONAL_VALUE, 'The formatter', 'list')
                ->end()
        ;
    }
}
