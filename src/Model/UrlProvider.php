<?php

namespace Hogosha\Monitor\Model;

use Hogosha\Monitor\Configuration\ConfigurationLoader;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class UrlProvider
{
    /**
     * $configurationLoader.
     *
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * Constructor.
     *
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * getUrls.
     *
     * @return array
     */
    public function getUrls()
    {
        $config = $this->configurationLoader->loadConfiguration();
        foreach ($config['urls'] as $name => $attribute) {
            $urls[] = new UrlInfo(
                $name,
                $attribute['url'],
                $attribute['timeout']
            );
        }

        return $urls;
    }
}
