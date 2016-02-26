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

namespace Hogosha\Monitor\Configuration;

use Symfony\Component\Yaml\Yaml;

class ConfigurationDumper
{
    /**
     * Dump configuration array to valid file content.
     *
     * @param array $config The configuration
     *
     * @return string The dumped configuration
     */
    public function dumpConfiguration(array $config)
    {
        $content = '';
        foreach ($config as $name => $section) {
            $content .= Yaml::dump([$name => $section], 4).PHP_EOL;
        }

        return $content;
    }
}
