<?php

namespace Irongate\Monitor\Console\Command;

use Symfony\Component\Console\Command\Command;

/**
 * BaseCommand.
 */
class BaseCommand extends Command
{
    /**
     * get.
     *
     * @param  string $serviceName Service name in the container
     * @return object
     */
    public function get($serviceName)
    {
        $app = $this->getApplication()->getApp();

        return $app[$serviceName];
    }
}
