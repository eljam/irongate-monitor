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
class HtmlValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testException.
     *
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage this string "/damme/" cannot be found
     */
    public function testException()
    {
        $htmlValidator = new HtmlValidator();
        $htmlValidator->check('chuck norris', '/damme/');
    }

    /**
     * testCheckTrue.
     */
    public function testCheckTrue()
    {
        $htmlValidator = new HtmlValidator();
        $this->assertNull($htmlValidator->check('chuck norris', '/chuck/'));
    }
}
