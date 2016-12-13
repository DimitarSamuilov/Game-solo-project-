<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\ResourceName;
use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitCost;
use ArchBundle\Entity\UnitName;
use ArchBundle\Form\ProduceUnitType;
use ArchBundle\Models\ViewModel\UnitViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UnitsController
 * @package ArchBundle\Controller
 * @Route("/units")
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class UnitsController extends BaseHelperController
{
    /**
     * @Route("/view",name="base_units_view")
     */
    public function ViewUnitsAction()
    {
        $em = $this->getDoctrine();
        $username = $this->getUser()->getUsername();
        $base = $em->getRepository(Base::class)->find($this->getBaseAction());
        $unitsRepo = $em->getRepository(Unit::class)->findBy(['base' => $base]);
        $viewArray = $this->getViewArray($unitsRepo);
        return $this->render('units/view.html.twig',['units'=>$viewArray,'username'=>$username]);
    }

    /**
     * @param $unitRepo
     * @return array
     */
    private function getViewArray($unitRepo)
    {
        $viewArray = [];

        foreach ($unitRepo as $unit) {
            /**
             * @var $unit Unit
             */
            $tempViewObject = new UnitViewModel();
            $tempViewObject->setName($unit->getUnitName()->getName());
            $tempViewObject->setCount($unit->getCount());
            $unitCosts = $unit->getUnitName()->getUnitCost();
            foreach ($unitCosts as $unitCost) {
                /**
                 * @var $unitCost UnitCost
                 */
                if ($unitCost->getResource()->getName() == "Wood") {
                    $tempViewObject->setWood($unitCost->getAmount());
                } else if ($unitCost->getResource()->getName() == "Coin") {
                    $tempViewObject->setCoin($unitCost->getAmount());
                }
            }
            $viewArray[] = $tempViewObject;
        }
        return $viewArray;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/produce",name="units_produce")
     */
    public function produceAction(Request $request)
    {
        $unit = new Unit();
        $unitName = $this->getDoctrine()->getRepository(UnitName::class)->findAll();
        $unit->setUnitName($unitName);
        $form = $this->createForm(ProduceUnitType::class, $unit);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->get('services')
                ->getUnitHelper()
                ->setProduction(
                    $this->getDoctrine()
                    , $unit->getUnitName()->getName()
                    , $unit->getCount()
                    , $this->getBaseAction()
                );
            return $this->redirectToRoute("base_units_view");
        }
        return $this->render("units/produce.html.twig", ['form' => $form->createView()]);
    }
}
