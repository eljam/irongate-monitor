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
use Hogosha\Monitor\Utils\Dom;

/**
 * Class XmlValidator.
 */
class XmlValidator implements ValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function check($value, $match)
    {
        try {
            $xml = $this->decode($value);
        } catch (\DomException $e) {
            throw new ValidatorException($e->getMessage());
        }

        if (0 === $xml->xpath($match)->length) {
            throw new ValidatorException(sprintf('this node "%s" does not exist', $match));
        }
    }

    /**
     * decode.
     *
     * @param string $content
     *
     * @throws ValidatorException
     *
     * @return \DomDocument
     */
    private function decode($content)
    {
        try {
            $result = new Dom($content);
        } catch (\DomException $e) {
            throw new ValidatorException('This xml is not valid');
        }

        return $result;
    }
}
