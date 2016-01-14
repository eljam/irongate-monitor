<?php

namespace Hogosha\Monitor\Console\Handler;

use Hogosha\Monitor\Client\GuzzleClient;
use Hogosha\Monitor\Configuration\ConfigurationLoader;
use Hogosha\Monitor\DependencyInjection\Exception\ConfigurationLoadingException;
use Hogosha\Monitor\Model\UrlProvider;
use Hogosha\Monitor\Renderer\RendererFactory;
use Hogosha\Monitor\Runner\Runner;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\IO\IO;

/**
 * @author Guillaume Cavana <guillaume.cavana@gmail.com>
 */
class RunHandler
{
    /**
     * $configurationLoader.
     *
     * @var ConfigurationLoader
     */
    protected $configurationLoader;

    /**
     * Constructor.
     *
     * @param ConfigurationLoader $configurationLoader
     */
    public function __construct(ConfigurationLoader $configurationLoader)
    {
        $this->configurationLoader = $configurationLoader;
    }

    /**
     * handle.
     *
     * @param Args $args
     * @param IO   $io
     *
     * @return int
     */
    public function handle(Args $args, IO $io)
    {
        $this->configurationLoader->setRootDirectory($args->getOption('config'));

        try {

            $runner = new Runner(
                new UrlProvider($this->configurationLoader),
                GuzzleClient::createClient()
            );

            $renderer = RendererFactory::create($args->getOption('format'), $io);
            $results = $runner->run();
            $renderer->render($results);

        } catch (ConfigurationLoadingException $e) {
            $io->writeLine(
                sprintf(
                    '<info>There is no configuration file in</info> "%s"',
                    $this->configurationLoader->getRootDirectory()
                )
            );
        }
    }
}
