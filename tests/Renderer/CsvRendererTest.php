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
     * testCsvRendererError.
     */
    public function testCsvRendererError()
    {
        $renderer = RendererFactory::create('csv', $this->io);
        $renderer->render($this->createResultCollection(true));

        $output = <<<CSV
"Global Status","Status Code",Name,"Reponse Time","Http Error Log","Validator Error Log"
FAIL,400,Example,0.42,"Couldn't resolve proxy name",

CSV;
        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * testCsvRenderer.
     */
    public function testCsvRenderer()
    {
        $renderer = RendererFactory::create('csv', $this->io);
        $renderer->render($this->createResultCollection());

        $output = <<<CSV
"Global Status","Status Code",Name,"Reponse Time","Http Error Log","Validator Error Log"
OK,200,Example,0.42,,

CSV;

        $this->assertInstanceOf(RendererInterface::class, $renderer);
        $this->assertSame($output, $this->io->fetchOutput());
    }

    /**
     * createResultCollection.
     * @param boolean $hasError
     */
    public function createResultCollection($hasError = false)
    {
        $errorLog = null;
        if ($hasError) {
            $errorLog = curl_strerror(5);
        }
        $result = new Result($this->createUrlInfo(), $hasError ? 400 : 200, 0.42, $errorLog);

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
