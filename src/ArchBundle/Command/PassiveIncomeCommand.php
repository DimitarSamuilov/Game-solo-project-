<?php

namespace ArchBundle\Command;

use ArchBundle\Entity\Base;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PassiveIncomeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('arch:passive_income_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $bases = $doctrine->getRepository(Base::class)->findAll();
        $baseService = $this->getContainer()->get('services')->getBaseGeneration();
        foreach ($bases as $base) {
            $baseService->resourcePassiveIncome($base->getId(), $doctrine);
        }
    }
}
