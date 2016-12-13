<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Structure;
use ArchBundle\Entity\StructureCost;
use ArchBundle\Models\ViewModel\StructureViewModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * Class BaseManagementController
 * @package ArchBundle\Controller
 * @Route("/base")
 */
class BaseManagementController extends BaseHelperController
{
    /**
     *
     * @Route("/view",name="base_view")
     */
    public function viewPlayerBasesAction()
    {
        $currentUser = $this->getUser();
        $bases = $this->getDoctrine()->getRepository(Base::class)->findBy(['user' => $this->getUser()]);
        return $this->render("base/view.html.twig", ['bases' => $bases, 'username' => $currentUser->getUsername()]);
    }

    /**
     *
     * @return Response
     * @Route("/structure" ,name="base_structure")
     */
    public function viewPlayerStructuresAction()
    {
        $id = $this->getBaseAction();
        $base = $this->getDoctrine()->getRepository(Base::class)->find($id);
        $structures = $this->getDoctrine()->getRepository(Structure::class)->findBy(['base' => $base]);
        $viewArray=$this->prepareStructureViewModel($structures);

        return $this->render("base/viewStructures.html.twig", ['structures' => $viewArray]);

    }


    private function prepareStructureViewModel($structures)
    {
        $resultViewArray = [];
        foreach ($structures as $structure) {
            /**
             * @var $structure Structure
             * @var $structureCost StructureCost
             */
            $tempViewObject = new StructureViewModel();
            $tempViewObject->setName($structure->getStructureName()->getName());
            $tempViewObject->setId($structure->getId());
            $tempViewObject->setUsername($this->getUser()->getUsername());
            $tempViewObject->setLevel($structure->getLevel());
            foreach ($structure->getStructureName()->getStructureCost() as $structureCost){
                if($structureCost->getResource()->getName()=="Wood"){
                    $tempViewObject->setWood($structureCost->getAmount()*($structure->getLevel()+1));
                }else if($structureCost->getResource()->getName()=="Coin"){
                    $tempViewObject->setCoin($structureCost->getAmount()*($structure->getLevel()+1));
                }
            }
            $resultViewArray[]=$tempViewObject;
        }

        return $resultViewArray;

    }

    /**
     * @return Response
     * @param $id
     * @Route("/base/change/{id}",name="base_change")
     */
    public function changeBaseAction($id)
    {
        $user = $this->getUser();
        $baseRepo = $this->getDoctrine()->getRepository(Base::class)->findOneBy(['id' => $id, 'user' => $user->getId()]);
        if ($baseRepo === null) {
            return $this->redirectToRoute("security_logout");
        }
        $this->get('session')->set('base_id', $id);
        return $this->redirectToRoute('game_index');
    }

    /**
     * @Route("/structure/upgrade/{id}",name="base_structure_upgrade")
     *
     */
    public function upgradeStructure($id)
    {

        $haveNeededResources = $this->get('services')->getStructureHelper()->setUpgrade($this->getDoctrine(), $id);
        if ($haveNeededResources) {
            $this->get('services')->getStructureHelper()->allocateUpgradeResources($this->getDoctrine(), $this->getBaseAction(), $id);
        }

        return $this->redirectToRoute("base_structure");
    }

}
