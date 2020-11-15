<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SongCronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:song')
            ->setDescription('Process the current song');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $song = $this->getContainer()->get('app.songprovider')->processCurrentSong();
        if ($song) {
            $output->writeln('<info>Done</info>');
        } else {
            $output->writeln('<error>Failed</error>');
        }

    }
}