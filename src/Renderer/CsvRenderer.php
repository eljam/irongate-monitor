<?php

namespace Hogosha\Monitor\Renderer;

use Hogosha\Monitor\Model\ResultCollection;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class CsvRenderer implements RendererInterface
{
    protected $io;

    /**
     * Constructor.
     *
     * @param IO $io
     */
    public function __construct(IO $io)
    {
        $this->io = $io;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ResultCollection $resultCollection)
    {
        $stream = fopen('php://temp', 'w');
        fputcsv(
            $stream,
            [
                'Name',
                'Status',
                'Reponse Time',
            ]
        );

        foreach ($resultCollection as $result) {
            fputcsv(
                $stream,
                [
                    $result->getName(),
                    $result->getStatusCode(),
                    $result->getReponseTime(),
                ]
            );
        }
        rewind($stream);
        $this->io->write(stream_get_contents($stream));
        fclose($stream);
    }
}
