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
                'Global Status',
                'Status Code',
                'Name',
                'Reponse Time',
                'Error Log',
            ]
        );

        $statusGuesser = new StatusGuesser();

        foreach ($resultCollection as $result) {
            fputcsv(
                $stream,
                [
                    $statusGuesser->isFailed($result) ? 'FAIL' : 'OK',
                    $result->getStatusCode(),
                    $result->getUrl()->getName(),
                    $result->getReponseTime(),
                    $result->getHandlerError(),
                ]
            );
        }
        rewind($stream);
        $this->io->write(stream_get_contents($stream));
        fclose($stream);
    }
}
