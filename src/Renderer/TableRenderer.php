<?php

namespace Monitor\Renderer;

use Monitor\Model\ResultCollection;
use Monitor\Renderer\RendererInterface;
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
            ->setHeaderRow(['Name', 'Status', 'Response Time']);

        foreach ($resultCollection as $result) {
            $table->addRow([
                $result->getName(),
                sprintf('<fg=green>%s</fg=green>', $result->getStatusCode()),
                $result->getReponseTime(),
            ]);
        }

        $table->render($this->io);
    }
}
