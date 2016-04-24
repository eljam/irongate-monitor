<?php

namespace Hogosha\Monitor\Validator;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class HtmlValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testException
     * @expectedException Hogosha\Monitor\Exception\ValidatorException
     * @expectedExceptionMessage this string "/damme/" cannot be found
     */
    public function testException()
    {
        $htmlValidator = new HtmlValidator();
        $htmlValidator->check('chuck norris', '/damme/');
    }

    /**
     * testCheckTrue
     */
    public function testCheckTrue()
    {
        $htmlValidator = new HtmlValidator();
        $this->assertNull($htmlValidator->check('chuck norris', '/chuck/'));
    }
}
