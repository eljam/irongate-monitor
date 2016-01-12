<?php

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
