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
