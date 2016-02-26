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

namespace Hogosha\Monitor\Portal;

use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Sdk\Api\Client;
use Hogosha\Sdk\Resource\Factory\ResourceFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Pusher.
 */
class Pusher
{
    /**
     * $client.
     *
     * @var Client
     */
    protected $client;

    /**
     * $urlProvider.
     *
     * @var UrlProvider
     */
    protected $urlProvider;

    /**
     * Constructor.
     *
     * @param UrlProvider         $urlProvider
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(
        UrlProvider $urlProvider,
        ConfigurationLoader $configurationLoader
    ) {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'username',
                'password',
                'base_uri',
            ]
        );

        $this->urlProvider = $urlProvider;
        $options = $configurationLoader->loadConfiguration();

        $options = $resolver->resolve($options['hogosha']);

        $this->client = new Client(
            [
                'username' => $options['username'],
                'password' => $options['password'],
                'base_uri' => $options['base_uri'],
            ]
        );
    }

    /**
     * push.
     *
     * @param ResultCollection $results
     */
    public function push(ResultCollection $results)
    {
        $resourceFactory = new ResourceFactory($this->client);
        $metricResource = $resourceFactory->get('metricPoint');

        foreach ($results as $result) {
            try {
                $metricResource->createMetricPoint([
                    'metric' => $this->urlProvider->getUrlByName($result->getName())->getMetricUuid(),
                    'value' => $result->getReponseTime() * 1000,
                    'datetime' => date('Y-m-d H:i:s'),
                ]);
            } catch (\Exception $e) {
                echo sprintf('There is an error %s', $e->getMessage());
            }
        }
    }
}
