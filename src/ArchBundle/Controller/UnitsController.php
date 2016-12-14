<?php

namespace ArchBundle\Controller;
use Alpha\B;
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
        $this->get('services')->getUnitHelper()->unitProductionProcessing($this->getBaseAction(),$this->getDoctrine());
        $username = $this->getUser()->getUsername();
        $base = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $unitsRepo = $this->getDoctrine()->getRepository(Unit::class)->findBy(['base' => $base]);
        $viewArray = $this->get('services')->getUnitHelper()->getViewArray($unitsRepo);
        return $this->render('units/view.html.twig', ['units' => $viewArray, 'username' => $username]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/produce",name="units_produce")
     */
    public function produceAction(Request $request)
    {
        $unitService = $this->get('services')->getUnitHelper();
        $unit = new Unit();
        $unitName = $this->getDoctrine()->getRepository(UnitName::class)->findAll();
        $unit->setUnitName($unitName);
        $form = $this->createForm(ProduceUnitType::class, $unit);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            if (!$unitService->haveNeededResources($unit->getUnitName(), $this->getBaseAction(), $unit->getCount(), $this->getDoctrine())) {
                return $this->render("units/produce.html.twig", ['form' => $form->createView()]);
            }
            //$unitService->beginProduction($unit->getUnitName(), $this->getBaseAction(), $unit->getCount(), $this->getDoctrine());
            return $this->redirectToRoute("base_units_view");
        }
        return $this->render("units/produce.html.twig", ['form' => $form->createView()]);
    }

}
