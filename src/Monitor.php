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

namespace Hogosha\Monitor;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class Monitor
{
    /**
     * @const VERSION Version of the application
     */
    const VERSION = '@package_version@';

    /**
     * @const CONFIG_FILENAME Filename of the configuration file
     */
    const CONFIG_FILENAME = '.hogosha-monitor.yml';

    /**
     * @const RENDERER_TYPE_TABLE Table formatter
     */
    const RENDERER_TYPE_TABLE = 'table';

    /**
     * @const RENDERER_TYPE_CSV Csv formatter
     */
    const RENDERER_TYPE_CSV = 'csv';
}
