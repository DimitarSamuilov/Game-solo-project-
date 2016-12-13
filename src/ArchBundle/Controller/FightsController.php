<?php

namespace ArchBundle\Controller;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use ArchBundle\Entity\User;
use ArchBundle\Form\AttackFormType;
use ArchBundle\Form\UnitAttackFormType;

use ArchBundle\Models\Utility\AttackFormHelper;
use ArchBundle\Models\Utility\FightingUnit;
use ArchBundle\Models\ViewModel\PlayerBaseModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FightsController
 * @package ArchBundle\Controller
 * @Route("/fight")
 */
class FightsController extends BaseHelperController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/players",name="fight_players")
     */
    public function listPlayerBases()
    {
        $bases = $this->getDoctrine()->getRepository(Base::class)->findAll();
        $currentBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render("fight/userBases.html.twig",
            [
                'bases' => $this->get('services')->getFightService()->getBasesView($bases, $currentBase),
                'currentUserId' => $this->getUser()->getId()
            ]);
    }

    /**
     *
     * @Route("/attackMenu",name="fight_attack_menu")
     */
    public function attackMenu(Request $request)
    {
        $attackerBase=$this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $service=$this->get('services')->getFightService();
        $before=$service->mapAttackerUnits($attackerBase->getUnits());
        $form=$this->createForm(AttackFormType::class,$attackerBase);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid()){

            var_dump($before);
            foreach ($attackerBase->getUnits() as $unit){
                var_dump($unit->getCount());
            }
           /*if($service->areMoreSoldiersAdded($before,$attackerBase->getUnits())){
               return $this->render("fight/attackMenu.html.twig",['form'=>$form->createView()]);
           }*/
        }
       return $this->render("fight/attackMenu.html.twig",['form'=>$form->createView()]);
    }
    /**
     * @Route("/test")
     */
    public function test()
    {
        $fighterService = $this->get('services')->getFightService();
        $doctrine = $this->getDoctrine();
        $attacker = $doctrine->getRepository(Base::class)->find(9);
        $defenderBase = $doctrine->getRepository(Base::class)->find(2);
        $fighterService->organiseAssault($attacker, $defenderBase, $attacker->getUnits(),$fighterService->mapAttackerUnits($attacker->getUnits()) ,$doctrine);
    }
}
