<?php

namespace ArchBundle\Controller;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\UnitName;
use ArchBundle\Form\AttackFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FightsController
 * @package ArchBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
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
        $currentBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $battles = $this->get('services')->getFightService()->getPlayerBattles($currentBase, $this->getDoctrine());
        foreach ($battles as $battle) {
            $this->get('services')->getFightService()->organiseAssault($battle, $this->getDoctrine());
        }
        $bases = $this->getDoctrine()->getRepository(Base::class)->findAll();
        $currentBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render("fight/userBases.html.twig",
            [
                'bases' => $this->get('services')->getViewHelper()->getBasesView($bases, $currentBase, $this->getDoctrine()),
                'currentUserId' => $this->getUser()->getId()
            ]);
    }

    /**
     *
     * @Route("/attackMenu/{id}",name="fight_attack_menu")
     */
    public function attackAction($id, Request $request)
    {
        $attackerBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $service = $this->get('services')->getFightService();
        $before = $service->mapAttackerUnits($attackerBase->getUnits());
        $form = $this->createForm(AttackFormType::class, $attackerBase);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            try {
                $service->areMoreSoldiersAdded($before, $attackerBase->getUnits());
                $defenderBase = $this->getDoctrine()->getRepository(Base::class)->find($id);
                $attackerUnits = $service->mapAttackerUnits($attackerBase->getUnits());
                $service->prepareBattle($attackerBase, $defenderBase, $attackerUnits, $before, $this->getDoctrine());
                return $this->redirectToRoute('fight_players');
            } catch (Exception $exception) {
                $this->get('session')->getFlashBag()->add('error', $exception->getMessage());
                return $this->render('fight/attackMenu.html.twig', ['form' => $form->createView()]);
            }
        }
        return $this->render("fight/attackMenu.html.twig", ['form' => $form->createView()]);
    }

    /**
     *
     * @Route("/test")
     */
    public function test()
    {
        $unitName = $this->getDoctrine()->getRepository(UnitName::class)->findOneBy(['name' => 'Templar']);
        var_dump($unitName->getDescription());
    }

}
