<?php

namespace Irongate\Monitor\Console\Command;

use Irongate\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * InitCommand
 */
class InitCommand extends BaseCommand
{
    /**
     * [$configuration description]
     * @var array
     */
    protected $configuration = array();

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Create a default configuration file if you do not have one')
            ->setHelp('php <info>bin/monitor</info> init')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $configurationLoader = $this->get('configuration.loader');

        try {
            $this->configuration = $configurationLoader->loadConfiguration();
        } catch (ConfigurationLoadingException $e) {
            $this->configuration = [
                'server' => [
                    'hostname' => null,
                    'port' => null,
                    'use_ssl' => null,
                    'auth' => [
                        'client_id' => null,
                        'client_secret' => null,
                        'username' => null,
                        'password' => null,
                    ],
                ],
                'sites' => [
                    'irongate' => [
                        'url' => 'http://google.com',
                        'timeout' => 1,
                        'status_code' => 200,
                        'service_uid' => ''
                    ],
                ],
            ];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        // Server
        if (!isset($this->configuration['server']['hostname'])) {
            $this->configuration['server']['hostname'] = $dialog->ask($output, '<info>Hostname</info> [<comment>irongate.dev</comment>]: ', 'irongate.dev');
        }
        if (!isset($this->configuration['server']['port'])) {
            $this->configuration['server']['port'] = $dialog->ask($output, '<info>Port</info> [<comment>80</comment>]: ', '80');
        }
        if (!isset($this->configuration['server']['use_ssl'])) {
            $this->configuration['server']['use_ssl'] = $dialog->askConfirmation($output, '<info>Enable ssl</info> [<comment>no</comment>]? ', false);
        }
        if (!isset($this->configuration['server']['auth']['username'])) {
            $currentUser = get_current_user();
            $this->configuration['server']['auth']['username'] = $dialog->ask($output, "<info>Username</info> [<comment>$currentUser</comment>]: ", $currentUser);
        }
        if (!isset($this->configuration['server']['auth']['password'])) {
            $this->configuration['server']['auth']['password'] = $dialog->askHiddenResponseAndValidate(
                $output,
                '<info>Password</info> []: ',
                function ($answer) {
                    if ('' === trim($answer)) {
                        throw new \RuntimeException('The password can not be empty.');
                    }

                    return $answer;
                },
                false,
                false
            );
        }
        if (!isset($this->configuration['server']['auth']['client_id'])) {
            $this->configuration['server']['auth']['client_id'] = $dialog->askAndValidate(
                $output,
                '<info>client_id</info> []: ',
                function ($answer) {
                    if ('' === trim($answer)) {
                        throw new \RuntimeException('The client_id can not be empty.');
                    }

                    return $answer;
                },
                false,
                false
            );
        }
        if (!isset($this->configuration['server']['auth']['client_secret'])) {
            $this->configuration['server']['auth']['client_secret'] = $dialog->askAndValidate(
                $output,
                '<info>client_secret</info> []: ',
                function ($answer) {
                    if ('' === trim($answer)) {
                        throw new \RuntimeException('The client_secret can not be empty.');
                    }

                    return $answer;
                },
                false,
                false
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getHelperSet()->get('dialog');
        $configurationLoader = $this->get('configuration.loader');

        // Dump configuration
        $content = $this->get('configuration.dumper')->dumpConfiguration($this->configuration);
        if ($input->isInteractive()) {
            $output->writeln(['', $content]);
        }

        if (!$dialog->askConfirmation($output, '<info>Do you confirm generation</info> [<comment>yes</comment>]? ')) {
            return 1;
        }

        file_put_contents($configurationLoader->getConfigurationFilepath(), $content);

        $output->writeln('<info>Creating monitor file</info>');
    }
}
