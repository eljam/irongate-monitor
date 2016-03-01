<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Guesser\StatusGuesser;
use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlInfo;

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
     */
    public function createResult()
    {
        $result = new Result($this->createUrlInfo(), 200, 0.42);

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
            null,
            null
        );
    }
}
