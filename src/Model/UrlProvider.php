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

namespace Hogosha\Monitor\Model;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UrlProvider.
 */
class UrlProvider
{
    /**
     * $urls.
     *
     * @var array
     */
    protected $urls = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults(
            [
                'metric_uuid' => null,
                'service_uuid' => null,
            ]
        );
        $resolver->setRequired(
            [
                'url',
                'method',
                'headers',
                'timeout',
                'status_code',
            ]
        );

        foreach ($config['urls'] as $name => $attribute) {
            $attribute = $resolver->resolve($attribute);
            $this->urls[$name] = new UrlInfo(
                $name,
                $attribute['url'],
                $attribute['method'],
                $attribute['headers'],
                $attribute['timeout'],
                $attribute['status_code'],
                $attribute['metric_uuid'],
                $attribute['service_uuid']
            );
        }
    }

    /**
     * getUrls.
     *
     * @return array
     */
    public function getUrls()
    {
        return $this->urls;
    }

    /**
     * getUrlByName.
     *
     * @param string $url
     *
     * @return UrlInfo
     */
    public function getUrlByName($url)
    {
        if (array_key_exists($url, $this->urls)) {
            return $this->urls[$url];
        }
    }
}
