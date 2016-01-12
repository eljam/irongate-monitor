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
        $this->file = sys_get_temp_dir().DIRECTORY_SEPARATOR.Monitor::CONFIG_FILENAME;

        // Dump the configuration set by the enduser
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
                'example.com' => [
                    'url' => 'https://www.example.com',
                    'timeout' => 1,
                    'status_code' => 200,
                    'service_uid' => '',
                ],
            ],
        ];

        $content = '';
        foreach ($configuration as $name => $section) {
            $content .= Yaml::dump([$name => $section], 4).PHP_EOL;
        }

        file_put_contents($this->file, $content);

        //Test if the enduser configuration is kept by the ConfigurationLoader
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
            $this->file,
            $configurationLoader->getConfigurationFilepath()
        );

        $this->assertEquals(
            [
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
                    'example.com' => [
                        'url' => 'https://www.example.com',
                        'timeout' => 1,
                        'status_code' => 200,
                        'service_uid' => '',
                    ],
                ],
            ],
            Yaml::parse(file_get_contents($this->file))
        );

        unlink($this->file);
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
            ],
            Yaml::parse(file_get_contents($testFile))
        );

        unlink($testFile);
    }
}
