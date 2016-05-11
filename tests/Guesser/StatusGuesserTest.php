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

use Hogosha\Monitor\Guesser\StatusGuesser;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\UrlInfo;
use Hogosha\Monitor\Validator\Validator;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class StatusGuesserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testIsFailed.
     */
    public function testIsFailed()
    {
        $statusGuesser = new StatusGuesser();
        $this->assertTrue($statusGuesser->isFailed($this->createResult()));
    }

    /**
     * testIsOk.
     */
    public function testIsOk()
    {
        $statusGuesser = new StatusGuesser();
        $this->assertFalse($statusGuesser->isOk($this->createResult()));
    }

    /**
     * createResult.
     *
     * @return Result
     */
    public function createResult()
    {
        $result = new Result($this->createUrlInfo(), 200, 0.42, null, false, null);

        return $result;
    }

    private function createUrlInfo()
    {
        return new UrlInfo(
            'Example',
            'http://example.com',
            'GET',
            [],
            1,
            404,
            (new Validator()),
            null,
            null
        );
    }
}
