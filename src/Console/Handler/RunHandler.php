<?php

namespace Hogosha\Monitor\Console\Handler;

use Hogosha\Monitor\Client\GuzzleClient;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Renderer\RendererFactory;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunHandler
{
    /**
     * $configurationLoader.
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * Constructor.
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * handle.
     * @param  Args $args
     * @param  IO   $io
     *
     * @return string
     */
    public function handle(Args $args, IO $io)
    {
        $this->configurationLoader->setRootDirectory($args->getOption('config'));

        $runner = new Runner(
            new UrlProvider($this->configurationLoader),
            GuzzleClient::createClient()
        );

        $renderer = RendererFactory::create($args->getOption('format'), $io);
        $results = $runner->run();
        $renderer->render($results);
    }
}
