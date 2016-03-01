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

namespace Hogosha\Monitor\Guesser;

use Hogosha\Monitor\Model\Result;

/**
 * Class StatusGuesser.
 */
class StatusGuesser
{
    const IDENTIFIED = 1;
    const RESOLVED = 0;

    /**
     * isOk.
     *
     * @param Result $result
     *
     * @return bool Return true is the condition is true
     */
    public function isOk(Result $result)
    {
        return $result->getUrl()->getExpectedStatus() == $result->getStatusCode();
    }

    /**
     * isFailed.
     *
     * @param Result $result
     *
     * @return bool Return true is the condition is false
     */
    public function isFailed(Result $result)
    {
        return !$this->isOk($result);
    }
}
