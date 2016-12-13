<?php

namespace ArchBundle\Controller;


use ArchBundle\Entity\Base;
use ArchBundle\Form\AttackFormType;
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
     * @Route("/",name="fight_players")
     */
    public function listPlayerBasesAction()
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
     * @Route("/attackMenu/{id}",name="fight_attack_menu")
     */
    public function attackAction($id,Request $request)
    {
        $fightService=$this->get('services')->getFightService();
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
            $mappedResults = $service->mapAttackerUnits($attackerBase->getUnits());
            $service->sendArmy($attackerBase, $defenderBase, $mappedResults,$before, $this->getDoctrine());
            $fightService->organiseAssault($attackerBase,$defenderBase,$this->getDoctrine());
            return $this->redirectToRoute('fight_players');
        }
        return $this->render("fight/attackMenu.html.twig", ['form' => $form->createView()]);
    }

}
