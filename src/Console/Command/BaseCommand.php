<?php

namespace Irongate\Monitor\Console\Command;

use Symfony\Component\Console\Command\Command;

class BaseCommand extends Command
{
    public function get($serviceName)
    {
        $app = $this->getApplication()->getApp();

        return $app[$serviceName];
    }

    /**
     * Returns the text representation of the command.
     *
     * @return string The string that represents the command
     */
    public function asText()
    {
        $app = $this->getApplication()->getApp();
        $txt = $app['app.signature']
               ."\n"
               .parent::asText();

        return $txt;
    }
}
