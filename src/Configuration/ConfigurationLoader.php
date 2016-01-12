<?php

namespace Hogosha\Monitor\Configuration;

use Hogosha\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class ConfigurationLoader
{
    /**
     * $rootDirectory.
     *
     * @var string
     */
    protected $rootDirectory;

    /**
     * $configFilepath.
     *
     * @var string
     */
    protected $configFilepath;

    /**
     * $filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * @param string $rootDirectory Directory containing the config file
     * @param string $filename      Configuration file name
     */
    public function __construct($rootDirectory, $filename)
    {
        $this->rootDirectory = $rootDirectory;
        $this->filename = $filename;
    }

    /**
     * loadConfiguration.
     *
     * @return mixed
     */
    public function loadConfiguration()
    {
        $filePath = $this->getConfigurationFilepath();

        if (!file_exists($filePath)) {
            throw new ConfigurationLoadingException(
                sprintf('Unable to find a configuration file in %s', $this->rootDirectory)
            );
        }

        return Yaml::parse(file_get_contents($filePath));
    }

    /**
     * getConfigurationFilepath.
     *
     * @return string The configuration filepath
     */
    public function getConfigurationFilepath()
    {
        return $this->configFilepath ?: $this->rootDirectory.DIRECTORY_SEPARATOR.$this->filename;
    }

    /**
     * setConfigurationFilepath.
     *
     * @param string $configFilepath The configuration filepath
     */
    public function setConfigurationFilepath($configFilepath)
    {
        $this->configFilepath = $configFilepath;
    }

    /**
     * getRootDirectory.
     *
     * @return string The root directory
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * setRootDirectory.
     *
     * @param string $rootDirectory The root directory
     */
    public function setRootDirectory($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }
}
