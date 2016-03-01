<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Model\Result;
use Hogosha\Monitor\Model\ResultCollection;
use Hogosha\Monitor\Model\UrlInfo;
use Webmozart\Console\Api\IO\IO;
use Webmozart\Console\IO\BufferedIO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class CsvRendererTest extends \PHPUnit_Framework_TestCase
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
     */
    public function testTableRenderer()
    {
        $renderer = RendererFactory::create('csv', $this->io);
        $renderer->render($this->createResultCollection());

        $output = <<<TABLE
"Global Status","Status Code",Name,"Reponse Time"
OK,200,Example,0.42

TABLE;

        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * createResultCollection.
     */
    public function createResultCollection()
    {
        $result = new Result($this->createUrlInfo(), 200, 0.42);

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
            null,
            null
        );
    }
}
