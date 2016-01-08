<?php

namespace Monitor\Renderer;

use Monitor\Model\ResultCollection;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
interface RendererInterface
{
    /**
     * render.
     * @param array $resultCollection Data that contains result objects
     * @return string
     */
    public function render(ResultCollection $resultCollection);
}
