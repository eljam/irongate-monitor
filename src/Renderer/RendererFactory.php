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
