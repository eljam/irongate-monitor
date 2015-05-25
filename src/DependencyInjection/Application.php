<?php

namespace Irongate\Monitor\DependencyInjection;

use Irongate\Monitor\Configuration\ConfigurationDumper;
use Irongate\Monitor\Configuration\ConfigurationLoader;
use Irongate\Monitor\Site\SiteProvider;
use Pimple\Container;

class Application extends Container
{
    const VERSION = '0.1-DEV';

    const CONFIG_FILENAME = '.irongate-monitor.yml';

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $app = $this;

        $this['app.signature'] = <<<SIGNATURE
  _____                             _
 |_   _|                           | |
   | |  _ __ ___  _ __   __ _  __ _| |_ ___
   | | | '__/ _ \| '_ \ / _` |/ _` | __/ _ \
  _| |_| | | (_) | | | | (_| | (_| | ||  __/
 |_____|_|  \___/|_| |_|\__, |\__,_|\__\___|
                         __/ |
                        |___/
SIGNATURE;

        $this['configuration.loader'] = function () {
            return new ConfigurationLoader(
                rtrim(getcwd(), DIRECTORY_SEPARATOR),
                self::CONFIG_FILENAME
            );
        };

        $this['configuration.dumper'] = function () {
            return new ConfigurationDumper();
        };

        $this['site.provider'] = function ($app) {
            return new SiteProvider($app['configuration.loader']);
        };
    }

    /**
     * getVersion.
     *
     * @return string
     */
    final public function getVersion()
    {
        return static::VERSION;
    }
}
