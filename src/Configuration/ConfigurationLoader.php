<?php

namespace Irongate\Monitor\Configuration;

use Irongate\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Symfony\Component\Yaml\Yaml;

class ConfigurationLoader
{
    /**
     * @var string
     */
    protected $rootDirectory;

    /**
     * @var string
     */
    protected $configFilepath;

    /**
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
     * @return string The configuration filepath
     */
    public function getConfigurationFilepath()
    {
        return $this->configFilepath ?: $this->rootDirectory.DIRECTORY_SEPARATOR.$this->filename;
    }

    /**
     * @param $configFilepath string The configuration filepath
     */
    public function setConfigurationFilepath($configFilepath)
    {
        $this->configFilepath = $configFilepath;
    }

    /**
     * @return string The root directory
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @param string $rootDirectory The root directory
     */
    public function setRootDirectory($rootDirectory)
    {
        $this->rootDirectory = $rootDirectory;
    }
}
