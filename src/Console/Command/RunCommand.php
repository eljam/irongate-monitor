<?php

namespace Irongate\Monitor\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use React\EventLoop\Factory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WyriHaximus\React\RingPHP\HttpClientAdapter;

class RunCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Launch the monitor process')
            ->setHelp('Monitor all url define in .irongate.yml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Url', 'Status']);

        $sites = $this->get('site.provider')->getSites();

        $progress = new ProgressBar($output, count($sites));
        $progress->setBarWidth(50);
        $progress->setBarCharacter('<info>=</info>');

        //Start the progress bar
        $progress->start();
        $loop = Factory::create();

        $guzzle = new Client([
            'handler' => new HttpClientAdapter($loop),
        ]);

        $rows = [];

        foreach ($sites as $i => $site) {
            $guzzle->get($site->getUrl(), [
                'connect_timeout' => $site->getTimeOut(),
                'future' => true,
            ])->then(function (Response $response) use (&$rows, $site, $progress) {
                $rows[$site->getName()][] = $site->getName();
                $rows[$site->getName()][] = $site->getUrl();
                $rows[$site->getName()][] = $response->getStatusCode();

                $progress->advance();

                sleep(1);
            }, function (\Exception $error) use (&$rows, $site, $output) {
                $output->writeln(
                    sprintf(
                        '<error>Theris is a error for %s stack %s:</error>',
                        $site->getName(),
                        $error->getMessage()
                    )
                );
            });
        }

        $loop->run();
        $progress->finish();

        //Add rows
        $table->setRows($rows);

        //Render the view
        $table->render();
    }
}
