<?php

namespace Irongate\Monitor\Console;

use Irongate\Monitor\Console\Command\InitCommand;
use Irongate\Monitor\Console\Command\RunCommand;
use Irongate\Monitor\DependencyInjection\Application;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * ConsoleApplication.
 */
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

    /**
     * __construct.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $txt = $this->app['app.signature']."\n".'Irongate Monitor Application';

        parent::__construct($txt, $this->app->getVersion());

        $this->add(new InitCommand());
        $this->add(new RunCommand());
    }
}
