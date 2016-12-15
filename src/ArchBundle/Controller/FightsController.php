<?php

namespace ArchBundle\Controller;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Battle;
use ArchBundle\Form\AttackFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class FightsController
 * @package ArchBundle\Controller
 * @Route("/fight")
 */
class FightsController extends BaseHelperController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/",name="fight_players")
     */
    public function listPlayerBasesAction()
    {
        $currentBase=$this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $battles=$this->get('services')->getFightService()->getPlayerBattles($currentBase,$this->getDoctrine());
        foreach ($battles as $battle){
            $this->get('services')->getFightService()->organiseAssault($battle,$this->getDoctrine());
        }
        $bases = $this->getDoctrine()->getRepository(Base::class)->findAll();
        $currentBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render("fight/userBases.html.twig",
            [
                'bases' => $this->get('services')->getFightService()->getBasesView($bases, $currentBase,$this->getDoctrine()),
                'currentUserId' => $this->getUser()->getId()
            ]);
    }

    /**
     *
     * @Route("/attackMenu/{id}",name="fight_attack_menu")
     */
    public function attackAction($id,Request $request)
    {
        $attackerBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $service = $this->get('services')->getFightService();
        $before = $service->mapAttackerUnits($attackerBase->getUnits());
        $form = $this->createForm(AttackFormType::class, $attackerBase);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if ($service->areMoreSoldiersAdded($before, $attackerBase->getUnits())) {
                return $this->render("fight/attackMenu.html.twig", ['form' => $form->createView()]);
            }
            $defenderBase = $this->getDoctrine()->getRepository(Base::class)->find($id);
            $attackerUnits = $service->mapAttackerUnits($attackerBase->getUnits());
            $service->prepareBattle($attackerBase, $defenderBase, $attackerUnits,$before, $this->getDoctrine());
            $service->getPlayerBattles($attackerBase,$this->getDoctrine());
            //$fightService->organiseAssault($attackerBase,$defenderBase,$this->getDoctrine());
            return $this->redirectToRoute('fight_players');
        }
        return $this->render("fight/attackMenu.html.twig", ['form' => $form->createView()]);
    }

    /**
     *
     * @Route("/test")
     */
    public function test()
    {
        $currentBase=$this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $battles=$this->get('services')->getFightService()->getPlayerBattles($currentBase,$this->getDoctrine());
        /**
         * @var  $battle Battle
         */
        var_dump(new \DateTime(null,new \DateTimeZone('Europe/Sofia')));
        var_dump(new \DateTime());
        foreach ($battles as $battle){
            var_dump($battle->getStartsOn());
            var_dump(new DateTime());
            //$this->get('services')->getFightService()->organiseAssault($battle,$this->getDoctrine());
        }
    }
}
