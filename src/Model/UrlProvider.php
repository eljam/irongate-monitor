<?php

namespace Hogosha\Monitor\Model;

use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'url',
                'method',
                'headers',
                'timeout',
                'status_code',
            ]
        );

        $config = $this->configurationLoader->loadConfiguration();
        foreach ($config['urls'] as $name => $attribute) {
            $attribute = $resolver->resolve($attribute);
            $urls[$name] = new UrlInfo(
                $name,
                $attribute['url'],
                $attribute['method'],
                $attribute['headers'],
                $attribute['timeout'],
                $attribute['status_code']
            );
        }

        return $urls;
    }
}
