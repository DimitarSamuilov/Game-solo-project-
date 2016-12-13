<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseHelperController extends Controller
{
    protected function getBaseAction()
    {
        $session = $this->get('session');
        /**
         * @var User
         */
        $user = $this->getUser();
        $baseId = $session->get('base_id');
        if ($baseId == null) {
            $baseId = $user->getBases()[0]->getId();
            $session->set('base_id', $baseId);
        }
        return $baseId;
    }


    public function renderResourcesAction()
    {
        $id = $this->getBaseAction();
        $base = $this->getDoctrine()->getRepository(Base::class)->find($id);
        return $this->render("base/partials/resources.html.twig", ['base' => $base]);

    }

    public function renderBaseSelectAction()
    {
        $currentPlanet = $this->getBaseAction();
        $user = $this->getUser();
        $bases = $this->getDoctrine()->getRepository(Base::class)->findBy(['user' => $user]);
        return $this->render("base/partials/basesSelect.html.twig", ['bases' => $bases, 'currentBase' => $currentPlanet]);
    }

    public function renderActiveBaseAction()
    {
        $base=$this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render("base/partials/activeBase.html.twig",['base'=>$base]);
    }

}
