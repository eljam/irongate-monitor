<?php

namespace Monitor\Renderer;

use Monitor\Monitor;
use Monitor\Renderer\RendererInterface;
use Monitor\Renderer\TableRenderer;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RendererFactory
{
    /**
     * create
     * @param  string $type
     * @param  IO     $io
     * @return RendererInterface
     */
    public static function create($type, IO $io)
    {
        if ($type == Monitor::RENDERER_TYPE_TABLE) {
            return new TableRenderer($io);
        } else {
            return new ListRenderer($io);
        }
    }
}
