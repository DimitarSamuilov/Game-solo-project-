<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Structure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class StructureController
 * @package ArchBundle\Controller
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @Route("/structure")
 */
class StructureController extends BaseHelperController
{
    /**
     *
     * @return Response
     * @Route("/" ,name="base_structure")
     */
    public function viewPlayerStructuresAction()
    {

        $service = $this->get('services')->getStructureHelper();
        $base = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $service->structureUpgradeProcessing($this->getBaseAction(), $this->getDoctrine());
        $structures = $base->getStructures();
        $viewArray = $service->prepareStructureViewModel($structures, $this->getUser());
        return $this->render("base/viewStructures.html.twig", ['structures' => $viewArray]);
    }

    /**
     * @Route("/upgrade/{structureId}",name="base_structure_upgrade")
     * @param $structureId
     * @return Response
     */
    public function upgradeStructure($structureId)
    {
        $service = $this->get('services')->getStructureHelper();
        $haveNeededResources = $service->haveResources($this->getDoctrine(), $structureId);
        if ($haveNeededResources) {
            $structure = $this->getDoctrine()->getRepository(Structure::class)->find($structureId);
            $service->beginUpgrade($structure, $this->getDoctrine());
            //$service->allocateUpgradeResources($this->getBaseAction(), $structure, $this->getDoctrine());
        }
        return $this->redirectToRoute("base_structure");
    }

    /**
     * @Route("/test")
     */
    public function test()
    {
        /*$compare=new DateTime('2016 -15-12');
        $this->get('services')->getStructureHelper()->structureUpgradeProcessing($this->getBaseAction(), $this->getDoctrine());
        var_dump($this->formatCountDownTime($compare));*/
    }


}
