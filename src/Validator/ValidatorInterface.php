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

namespace Hogosha\Monitor\Validator;

/**
 * Class ValidatorInterface.
 */
interface ValidatorInterface
{
    /**
     * check.
     *
     * @param string $value Value to check
     * @param string $match Value to match
     *
     * @return bool
     */
    public function check($value, $match);
}
