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


}
