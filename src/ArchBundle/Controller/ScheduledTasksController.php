<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Battle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ScheduledTasksController extends Controller
{
    /**
     * @Route("/unitProductionProcess",name="unit_production_process")
     */
    public function unitProductionAction()
    {
        $doctrine = $this->getDoctrine();
        $bases = $doctrine->getRepository(Base::class)->findAll();
        $unitProductionService = $this->get('services')->getUnitHelper();
        /**
         * @var Base
         */
        foreach ($bases as $base) {
            $unitProductionService->unitProductionProcessing($base->getId(), $doctrine);
        }
        return $this->render(':game:emptyPage.html.twig');
    }

    /**
     * @Route("/battleOutcomeProcess",name="battle_outcome_process")
     */
    public function battleOutcomeAction()
    {
        $doctrine = $this->getDoctrine();
        $battles = $doctrine->getRepository(Battle::class)->findAll();
        foreach ($battles as $battle) {
            $this->get('services')->getFightService()->organiseAssault($battle, $doctrine);
        }
        return $this->render(':game:emptyPage.html.twig');
    }

    /**
     * @Route("/structureUpgradeProcess",name="structure_upgrade_process")
     */
    public function structureUpgradesAction()
    {
        $structureService = $this->get('services')->getStructureHelper();
        $doctrine = $this->getDoctrine();
        $bases = $doctrine->getRepository(Base::class)->findAll();
        foreach ($bases as $base) {
            $structureService->structureUpgradeProcessing($base->getId(), $doctrine);
        }
        return $this->render(':game:emptyPage.html.twig');
    }

    /**
     * @Route("/basePassiveIncome",name="base_passive_income")
     */
    public function resourceIncome()
    {
        $bases = $this->getDoctrine()->getRepository(Base::class)->findAll();
        $baseService = $this->get('services')->getBaseGeneration();
        $doctrine = $this->getDoctrine();
        foreach ($bases as $base) {
            $baseService->resourcePassiveIncome($base->getId(), $doctrine);
        }
        return $this->render(':game:emptyPage.html.twig');

    }
}
