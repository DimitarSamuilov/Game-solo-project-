<?php

namespace ArchBundle\Command;

use ArchBundle\Entity\Base;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StructureUpgradeCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('arch:structure_upgrade_command')
            ->setDescription('Structure upgrade Command');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $structureService=$this->getContainer()->get('services')->getStructureHelper();
        $doctrine =$this->getContainer()->get('doctrine');
        $bases=$doctrine->getRepository(Base::class)->findAll();
        foreach ($bases as $base) {
            $structureService->structureUpgradeProcessing($base->getId(),$doctrine);
        }
    }
}
