<?php

namespace Irongate\Monitor\Console;

use Irongate\Monitor\Console\Command\InitCommand;
use Irongate\Monitor\Console\Command\RunCommand;
use Irongate\Monitor\DependencyInjection\Application;
use Symfony\Component\Console\Application as BaseApplication;

class ConsoleApplication extends BaseApplication
{
    /**
     * $app.
     *
     * @var Application
     */
    private $app;

    /**
     * getApp.
     *
     * @return Application
     */
    public function getApp()
    {
        return $this->app;
    }

    public function __construct(Application $app)
    {
        $this->app = $app;

        parent::__construct('Irongate Monitor Application', $this->app->getVersion());

        $this->add(new InitCommand());
        $this->add(new RunCommand());

        // $this->setDefaultCommand('run');
    }
}
