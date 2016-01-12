<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Renderer\RendererFactory;
use Hogosha\Monitor\Renderer\RendererInterface;
use Hogosha\Monitor\Renderer\TableRenderer;
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
     * testTableRenderer.
     * @return void
     */
    public function testTableRenderer()
    {
        $renderer = RendererFactory::create('table', $this->io);
        $renderer->render($this->createResultCollection());

        $output = <<<TABLE
+---------+--------+---------------+
| Name    | Status | Response Time |
+---------+--------+---------------+
| Example | 200    | 0.42          |
+---------+--------+---------------+

TABLE;

        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * createResultCollection.
     * @return void
     */
    public function createResultCollection()
    {
        $result = new Result('Example', 200, 0.42);

        $resultCollection = new ResultCollection();
        $resultCollection->append($result);

        return $resultCollection;
    }
}
