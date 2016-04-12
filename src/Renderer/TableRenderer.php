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
use Webmozart\Console\UI\Component\Table;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class TableRenderer implements RendererInterface
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
        $table = new Table();
        $table
            ->setHeaderRow(['Global Status', 'Status Code', 'Name', 'Response Time', 'Error Log']);

        $statusGuesser = new StatusGuesser();

        foreach ($resultCollection as $result) {
            $color = $statusGuesser->isFailed($result) ? 'red' : 'green';
            $table->addRow([
                $statusGuesser->isFailed($result) ? 'FAIL' : 'OK',
                sprintf(
                    '<bg=%s>%s</>',
                    $color,
                    $result->getStatusCode()
                ),
                $result->getUrl()->getName(),
                $result->getReponseTime(),
                sprintf(
                    '<bg=red>%s</>',
                    $result->getHandlerError()
                ),
            ]);
        }

        $table->render($this->io);
    }
}
