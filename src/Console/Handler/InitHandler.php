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

use Hogosha\Monitor\Configuration\ConfigurationDumper;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Hogosha\Monitor\Monitor;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class InitHandler
{
    /**
     * $configurationLoader.
     *
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * $configurationDumper.
     *
     * @var ConfigurationDumper
     */
    protected $configurationDumper;

    /**
     * Constructor.
     *
     * @param ConfigurationLoader $configurationLoader
     * @param ConfigurationDumper $configurationDumper
     * @param Filesystem          $filesystem
     */
    public function __construct(
        ConfigurationLoader $configurationLoader,
        ConfigurationDumper $configurationDumper,
        Filesystem $filesystem
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->configurationDumper = $configurationDumper;
        $this->filesystem = $filesystem;
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
        $configFileExist = true;
        $overwrite = is_string($args->getOption('force'));

        try {
            $this->configurationLoader->setRootDirectory($args->getOption('config'));
            $configuration = $this->configurationLoader->loadConfiguration();
        } catch (ConfigurationLoadingException $e) {
            $configFileExist = false;
        }

        if (!$configFileExist || $overwrite) {
            $configuration = [
                'urls' => [
                    'google' => [
                        'url' => 'https://www.google.fr',
                        'method' => 'GET',
                        'headers' => [],
                        'timeout' => 1,
                        'validator' => [],
                        'status_code' => 200,
                        'metric_uuid' => null,
                        'service_uuid' => null,
                    ],
                ],
                'hogosha_portal' => [
                    'username' => '',
                    'password' => '',
                    'base_uri' => 'http://localhost:8000/api/',
                    'metric_update' => false,
                    'incident_update' => false,
                    'default_failed_incident_message' => 'An error as occured, we are investigating %service_name%',
                    'default_resolved_incident_message' => 'The service %service_name% is back to normal',
                ],
            ];

            // Dump configuration
            $content = $this->configurationDumper->dumpConfiguration($configuration);
            $this->filesystem->dumpFile(
                $this->configurationLoader->getConfigurationFilepath(),
                $content
            );
            $io->writeLine('<info>Creating monitor file</info>');
        } else {
            $io->writeLine(
                sprintf(
                    '<info>You already have a configuration file in</info> "%s"',
                    $this->configurationLoader->getConfigurationFilepath()
                )
            );
        }
    }
}
