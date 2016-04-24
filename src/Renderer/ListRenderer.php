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
class ListRenderer implements RendererInterface
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
        $format = '[%s][%s] %s - %s%s%s';

        $errorFormat = ' - <bg=red>%s</>';

        $statusGuesser = new StatusGuesser();

        foreach ($resultCollection as $result) {
            $errorLog = $result->getHandlerError();
            $validatorErrorLog = $result->getValidatorError();

            $this->io->write(
                sprintf(
                    $format,
                    $statusGuesser->isFailed($result) ? 'FAIL' : 'OK',
                    $result->getStatusCode(),
                    $result->getUrl()->getName(),
                    $result->getReponseTime(),
                    (null !== $errorLog) ? sprintf($errorFormat, $errorLog) : '',
                    (null !== $validatorErrorLog) ? sprintf($errorFormat, $validatorErrorLog) : ''
                )."\n"
            );
        }
    }
}
