<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlInfo;
use Hogosha\Monitor\Validator\Validator;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\IO\BufferedIO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class ListRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var BufferedIO
     */
    private $io;

    protected function setUp()
    {
        $this->io = new BufferedIO();
    }

    /**
     * testListRendererError.
     */
    public function testListRendererError()
    {
        $renderer = RendererFactory::create('list', $this->io);
        $renderer->render($this->createResultCollection(true));

        $output = <<<LIST
[FAIL][400] Example - 0.42 - Couldn't resolve proxy name - Validation Error

LIST;

        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * testTableRenderer.
     */
    public function testListRenderer()
    {
        $renderer = RendererFactory::create('list', $this->io);
        $renderer->render($this->createResultCollection());

        $output = <<<LIST
[OK][200] Example - 0.42

LIST;

        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * createResultCollection.
     * @param boolean $hasError
     *
     * @return ResultCollection
     */
    public function createResultCollection($hasError = false)
    {
        $errorLog = null;
        $validationErrorLog = null;

        if ($hasError) {
            $errorLog = curl_strerror(5);
            $validationErrorLog = 'Validation Error';
        }

        $result = new Result($this->createUrlInfo(), $hasError ? 400 : 200, 0.42, $errorLog, $hasError ? false : true, $validationErrorLog);

        $resultCollection = new ResultCollection();
        $resultCollection->append($result);

        return $resultCollection;
    }

    private function createUrlInfo()
    {
        return new UrlInfo(
            'Example',
            'http://example.com',
            'GET',
            [],
            1,
            200,
            (new Validator()),
            null,
            null
        );
    }
}
