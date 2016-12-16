<?php

namespace ArchBundle\Command;

use ArchBundle\Entity\Base;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnitProductionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('arch:unit_production_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine=$this->getContainer()->get('doctrine');
        $bases=$doctrine->getRepository(Base::class)->findAll();
        $unitProductionService=$this->getContainer()->get('services')->getUnitHelper();
        /**
         * @var Base
         */
        foreach ($bases as $base){
            $unitProductionService->unitProductionProcessing($base->getId(),$doctrine);
        }
    }
}
