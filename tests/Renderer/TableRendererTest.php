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
class TableRendererTest extends \PHPUnit_Framework_TestCase
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
     * testTableRendererError.
     */
    public function testTableRendererError()
    {
        $renderer = RendererFactory::create('table', $this->io);
        $renderer->render($this->createResultCollection(true));

        $output = <<<TABLE
+--------+--------+---------+----------+--------------------+------------------+
| Global | Status | Name    | Response | Http Error Log     | Validator Error  |
| Status | Code   |         | Time     |                    | Log              |
+--------+--------+---------+----------+--------------------+------------------+
| FAIL   | 400    | Example | 0.42     | Couldn't           | Validation Error |
|        |        |         |          | resolve proxy name |                  |
+--------+--------+---------+----------+--------------------+------------------+

TABLE;
        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * testTableRenderer.
     */
    public function testTableRenderer()
    {
        $renderer = RendererFactory::create('table', $this->io);
        $renderer->render($this->createResultCollection());

        $output = <<<TABLE
+--------+--------+---------+----------+----------------+---------------------+
| Global | Status | Name    | Response | Http Error Log | Validator Error Log |
| Status | Code   |         | Time     |                |                     |
+--------+--------+---------+----------+----------------+---------------------+
| OK     | 200    | Example | 0.42     |                |                     |
+--------+--------+---------+----------+----------------+---------------------+

TABLE;
        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * createResultCollection.
     * @param boolean $hasError
     *
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
