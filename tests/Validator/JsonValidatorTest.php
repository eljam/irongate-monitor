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
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class JsonValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testCheckFalse.
     *
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage PropertyAccessor requires a graph of objects or arrays to operate on, but it found type "string" while trying to traverse path "name.toto" at property "toto".
     */
    public function testException()
    {
        $htmlValidator = new JsonValidator();
        $htmlValidator->check('{"name": "Chuck Norris"}', 'name.toto');
    }

    /**
     * testCheckFalse.
     *
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage This json is not valid
     */
    public function testInvalidJson()
    {
        $htmlValidator = new JsonValidator();
        $htmlValidator->check('{"name": "Chuck Norris"', 'name.toto');
    }
}
