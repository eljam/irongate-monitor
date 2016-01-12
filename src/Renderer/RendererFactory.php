<?php

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
        if ($type == Monitor::RENDERER_TYPE_TABLE) {
            return new TableRenderer($io);
        } else {
            return new CsvRenderer($io);
        }
    }
}
