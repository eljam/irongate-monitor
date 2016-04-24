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
use Symfony\Component\PropertyAccess\Exception\RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class JsonValidator.
 */
class JsonValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function check($value, $match)
    {
        $accessor = new PropertyAccessor();

        try {
            $json = $this->decode($value);
            $accessor->getValue($json, $match);
        } catch (RuntimeException $e) {
            throw new ValidatorException($e->getMessage());
        }
    }

    private function decode($content)
    {
        $result = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ValidatorException('This json is not valid');
        }

        return $result;
    }
}
