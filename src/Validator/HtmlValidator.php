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

use Hogosha\Monitor\Exception\ValidatorException;

/**
 * Class HtmlValidator.
 */
class HtmlValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function check($value, $match)
    {
        preg_match(sprintf('%s', $match), $value, $matches);

        if (false == isset($matches[0])) {
            throw new ValidatorException(sprintf('this string "%s" cannot be found', $match));
        }
    }
}
