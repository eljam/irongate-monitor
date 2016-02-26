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

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Monitor;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RendererFactory
{
    /**
     * create.
     *
     * @param string $type
     * @param IO     $io
     *
     * @return RendererInterface
     */
    public static function create($type, IO $io)
    {
        switch ($type) {
            case Monitor::RENDERER_TYPE_TABLE:
                return new TableRenderer($io);
                break;
            case Monitor::RENDERER_TYPE_CSV:
                return new CsvRenderer($io);
                break;
            default:
                return new ListRenderer($io);
                break;
        }
    }
}
