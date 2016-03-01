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

namespace Hogosha\Monitor\Pusher;

use Hogosha\Monitor\Model\ResultCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PusherManager.
 */
class PusherManager
{
    protected $pushers = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'username',
                'password',
                'base_uri',
                'default_resolved_incident_message',
                'default_failed_incident_message',
                'metric_update',
                'incident_update',
            ]
        );

        $options = $resolver->resolve($config['hogosha_portal']);

        if ($options['metric_update']) {
            $this->pushers['metric'] = new MetricPusher($options);
        }

        if ($options['incident_update']) {
            $this->pushers['incident'] = new IncidentPusher($options);
        }
    }

    /**
     * push.
     *
     * @param ResultCollection $results
     */
    public function push(ResultCollection $results)
    {
        foreach ($results as $result) {
            foreach ($this->pushers as $pusher) {
                $pusher->push($result);
            }
        }
    }
}
