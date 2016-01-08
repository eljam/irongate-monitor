<?php

namespace Monitor\Console\Handler;

use Monitor\Configuration\ConfigurationDumper;
use Monitor\Configuration\ConfigurationLoader;
use Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class InitHandler
{
    /**
     * $configurationLoader.
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * $configurationDumper.
     * @var ConfigurationDumper
     */
    protected $configurationDumper;

    /**
     * Constructor.
     * @param ConfigurationLoader $configurationLoader
     * @param ConfigurationDumper $configurationDumper
     */
    public function __construct(
        ConfigurationLoader $configurationLoader,
        ConfigurationDumper $configurationDumper
    ) {
        $this->configurationLoader = $configurationLoader;
        $this->configurationDumper = $configurationDumper;
    }

    /**
     * handle.
     * @param  Args    $args
     * @param  IO      $io
     * @param  Command $command
     * @return integer
     */
    public function handle(Args $args, IO $io, Command $command)
    {
        try {
            $configuration = $this->configurationLoader->loadConfiguration();
        } catch (ConfigurationLoadingException $e) {
            $configuration = [
                'server' => [
                    'hostname' => null,
                    'port' => null,
                    'use_ssl' => null,
                    'auth' => [
                        'username' => null,
                        'password' => null,
                    ],
                ],
                'urls' => [
                    'google' => [
                        'url' => 'https://www.google.fr',
                        'timeout' => 1,
                        'status_code' => 200,
                        'service_uid' => '',
                    ],
                ],
            ];
        }

        // Dump configuration
        $content = $this->configurationDumper->dumpConfiguration($configuration);

        file_put_contents($this->configurationLoader->getConfigurationFilepath(), $content);

        $io->writeline('<info>Creating monitor file</info>');
    }
}
