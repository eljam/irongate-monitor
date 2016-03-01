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

use Hogosha\Monitor\Model\Result;
use Hogosha\Sdk\Resource\Factory\ResourceFactory;

/**
 * Class MetricPusher.
 */
class MetricPusher extends AbstractPusher
{
    /**
     * {@inheridoc}.
     */
    public function push(Result $result)
    {
        $resourceFactory = new ResourceFactory($this->getClient());
        $metricResource = $resourceFactory->get('metricPoint');

        try {
            $metricResource->createMetricPoint([
                'metric' => $result->getUrl()->getMetricUuid(),
                'value' => $result->getReponseTime() * 1000,
                'datetime' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            echo sprintf('An error has occured %s', $e->getMessage());
        }
    }
}
