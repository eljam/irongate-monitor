<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Model\ResultCollection;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
interface RendererInterface
{
    /**
     * render.
     *
     * @param array $resultCollection Data that contains result objects
     *
     * @return string
     */
    public function render(ResultCollection $resultCollection);
}
