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

use Hogosha\Monitor\Guesser\StatusGuesser;
use Hogosha\Monitor\Model\Result;
use Hogosha\Sdk\Resource\Factory\ResourceFactory;

/**
 * Class IncidentPusher.
 */
class IncidentPusher extends AbstractPusher
{
    /**
     * {@inheritdoc}
     */
    public function push(Result $result)
    {
        $statusGuesser = new StatusGuesser();

        $resourceFactory = new ResourceFactory($this->getClient());
        $incidentResource = $resourceFactory->get('incident');

        $serviceResource = $resourceFactory->get('service');
        $service = $serviceResource->getService($result->getUrl()->getServiceUuid());

        $alreadyInFailure = false;

        if ($service['status'] != StatusGuesser::RESOLVED) {
            $alreadyInFailure = true;
        }

        try {
            // If the service is not already in failure,
            // we check if there is a failure.
            if (false === $alreadyInFailure && $statusGuesser->isFailed($result)) {
                $incidentResource->createIncident([
                    'name' => str_replace('%service_name%', $service['name'], $this->getDefaultFailedIncidentMessage()),
                    'service' => $result->getUrl()->getServiceUuid(),
                    'status' => StatusGuesser::IDENTIFIED,
                    'error_log' => sprintf('%s %s', $result->getHandlerError(), $result->getValidatorError()),
                ]);
            }

            // If the service is already in failture,
            // we check if it's back to normal and create a incident resolver
            if ($alreadyInFailure && $statusGuesser->isOk($result)) {
                $incidentResource->createIncident([
                    'name' => str_replace('%service_name%', $service['name'], $this->getDefaultResolvedIncidentMessage()),
                    'service' => $result->getUrl()->getServiceUuid(),
                    'status' => StatusGuesser::RESOLVED,
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception(sprintf('An error has occured %s', $e->getMessage()));
        }
    }
}
