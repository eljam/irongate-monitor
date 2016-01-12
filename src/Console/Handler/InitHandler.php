<?php

namespace Hogosha\Monitor\Console\Handler;

use Hogosha\Monitor\Configuration\ConfigurationDumper;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
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
        $this->configurationLoader->setRootDirectory($args->getOption('config'));
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
            // Dump configuration
            $content = $this->configurationDumper->dumpConfiguration($configuration);
            $this->filesystem->dumpFile(
                $this->configurationLoader->getConfigurationFilepath(),
                $content
            );
            $io->writeLine('<info>Creating monitor file</info>');
        }

        $io->writeLine(
            sprintf(
                '<info>You already have a configuration file in</info> "%s"',
                $this->configurationLoader->getConfigurationFilepath()
            )
        );
    }
}
