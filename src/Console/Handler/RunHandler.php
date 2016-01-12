<?php

namespace Hogosha\Monitor\Console\Handler;

use Hogosha\Monitor\Renderer\RendererFactory;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\Adapter\IOOutput;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunHandler
{
    /**
     * $runner.
     * @var Runner
     */
    protected $runner;

    /**
     * Constructor.
     * @param Runner $runner
     */
    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
    }

    /**
     * handle.
     * @param  Args    $args
     * @param  IO      $io
     * @param  Command $command
     * @return string
     */
    public function handle(Args $args, IO $io, Command $command)
    {
        $renderer = RendererFactory::create($args->getOption('format'), $io);
        $results = $this->runner->run();
        $renderer->render($results);
    }
}
