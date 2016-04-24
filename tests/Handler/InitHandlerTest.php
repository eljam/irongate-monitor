<?php

namespace Hogosha\Monitor\Client;

use Hogosha\Monitor\Configuration\ConfigurationDumper;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Console\Handler\InitHandler;
use Hogosha\Monitor\Monitor;
use Prophecy\Argument;
use Prophecy\type;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class InitHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testExistingConfigurationFile.
     */
    public function testExistingConfigurationFile()
    {
        $file = sys_get_temp_dir().DIRECTORY_SEPARATOR.Monitor::CONFIG_FILENAME;

        // Dump the configuration set by the enduser
        $configuration = [
            'urls' => [
                'example.com' => [
                    'url' => 'https://www.example.com',
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
            ]
        ];

        $content = '';
        foreach ($configuration as $name => $section) {
            $content .= Yaml::dump([$name => $section], 4).PHP_EOL;
        }

        file_put_contents($file, $content);

        //Test if the enduser configuration is kept by the ConfigurationLoader
        $configurationLoader = new ConfigurationLoader();

        $configurationDumper = new ConfigurationDumper();
        $filesystem = new Filesystem();

        $argsMock = $this->prophesize(Args::class);
        $argsMock
            ->getOption('config')
            ->willReturn(sys_get_temp_dir());
        $argsMock
            ->getOption('force')
            ->willReturn(null);

        $ioMock = $this->prophesize(IO::class);
        $ioMock
            ->writeLine(Argument::type('string'))
            ->shouldBeCalled();

        $commandMock = $this->prophesize(Command::class);

        $initHandler = new InitHandler(
            $configurationLoader,
            $configurationDumper,
            $filesystem
        );

        $initHandler->handle(
            $argsMock->reveal(),
            $ioMock->reveal(),
            $commandMock->reveal()
        );

        // Test the configuration of the enduser file
        $this->assertEquals(
            $file,
            $configurationLoader->getConfigurationFilepath()
        );

        $this->assertEquals(
            [
                'urls' => [
                    'example.com' => [
                        'url' => 'https://www.example.com',
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
                ]
            ],
            Yaml::parse(file_get_contents($file))
        );

        unlink($file);
    }

    /**
     * testDefaultConfigurationFile.
     */
    public function testDefaultConfigurationFile()
    {
        $testFile = sys_get_temp_dir().DIRECTORY_SEPARATOR.Monitor::CONFIG_FILENAME;

        $configurationLoader = new ConfigurationLoader();
        $configurationDumper = new ConfigurationDumper();
        $filesystem = new Filesystem();

        $argsMock = $this->prophesize(Args::class);
        $argsMock
            ->getOption(Argument::type('string'))
            ->willReturn(sys_get_temp_dir());
        $ioMock = $this->prophesize(IO::class);
        $ioMock
            ->writeLine(Argument::type('string'))
            ->shouldBeCalled();

        $commandMock = $this->prophesize(Command::class);

        $initHandler = new InitHandler(
            $configurationLoader,
            $configurationDumper,
            $filesystem
        );

        $initHandler->handle(
            $argsMock->reveal(),
            $ioMock->reveal(),
            $commandMock->reveal()
        );

        // Test the configuration of the enduser file
        $this->assertEquals(
            $testFile,
            $configurationLoader->getConfigurationFilepath()
        );

        $this->assertEquals(
            [
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
                ]
            ],
            Yaml::parse(file_get_contents($testFile))
        );

        unlink($testFile);
    }
}
