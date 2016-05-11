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
class XmlValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $xml;

    public function setUp()
    {
        $this->xml = <<<XML
<?xml version="1.0"?>
<root>
    <name>chuck</name>
    <lastname>norris</lastname>
</root>
XML;
    }

    /**
     * testCheckTrue.
     */
    public function testCheckTrue()
    {
        $xmlValidator = new XmlValidator();
        $this->assertNull($xmlValidator->check($this->xml, '//name'));
    }

    /**
     * testException.
     *
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage this node "//nickname" does not exist
     */
    public function testException()
    {
        $xmlValidator = new XmlValidator();
        $xmlValidator->check($this->xml, '//nickname');
    }

    /**
     * testInvalidXml.
     *
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage This xml is not valid
     */
    public function testInvalidXml()
    {
        $xmlValidator = new XmlValidator();
        $xmlValidator->check('<xml></xml', '//nickanme');
    }
}
