<?php

namespace ArchBundle\Command;

use ArchBundle\Entity\Battle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BattleCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('arch:battle_command')
            ->setDescription('Battle process command');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $battles = $doctrine->getRepository(Battle::class)->findAll();
        foreach ($battles as $battle) {
            $this->getContainer()->get('services')->getFightService()->organiseAssault($battle, $doctrine);
        }
    }
}
