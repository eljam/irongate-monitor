<?php

namespace Hogosha\Monitor\Renderer;

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
        foreach ($resultCollection as $result) {
            $this->io->write('- ');
            $this->io->write(sprintf('<fg=white>%s</fg=white>', $result->getName()));
            $this->io->write(' = ');
            $this->io->write(sprintf('<fg=green>%s</fg=green>', $result->getStatusCode()));
            $this->io->write(' = ');
            $this->io->write($result->getReponseTime());
            $this->io->write("\n");
        }
    }
}
