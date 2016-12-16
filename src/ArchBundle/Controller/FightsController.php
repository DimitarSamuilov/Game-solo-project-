<?php

namespace ArchBundle\Controller;


use ArchBundle\Entity\Base;
use ArchBundle\Entity\Battle;
use ArchBundle\Entity\StructureUpgrade;
use ArchBundle\Form\AttackFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

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
            }catch (Exception $exception){
                $this->get('session')->getFlashBag()->add('error',$exception->getMessage());
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
        $currentBase = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $battles = $this->get('services')->getFightService()->getPlayerBattles($currentBase, $this->getDoctrine());
        /**
         * @var  $battle Battle
         */
        $currentTime = new \DateTime();
        $timestamp = $currentTime->getTimestamp();
        $compare = new \DateTime('2016-12-15 20:07');
        var_dump($compare);
        //$time=$this->getDoctrine()->getRepository(StructureUpgrade::class)->find(11)->getFinishesOn();
        var_dump($currentTime->diff($compare)->format('%d days %h hours %i minutes'));
        $differences = $compare->getTimestamp() - $timestamp;
        $arr = [];
        $arr['days'] = floor($differences / 86400);
        $arr['hours'] = floor(($differences % 86400) / 3600);
        $arr['minutes'] = floor(($differences % 3600) / 60);
        $arr['seconds'] = floor($differences % 60);
        var_dump($arr);

    }

}
