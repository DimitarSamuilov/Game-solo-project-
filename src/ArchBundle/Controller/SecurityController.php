<?php

namespace ArchBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * @Route("/login",name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
    {
        $helper=$this->get('security.authentication_utils');

        return $this->render('security/login.html.twig',[
            'lastUser'=>$helper->getLastUsername(),
            'error'=>$helper->getLastAuthenticationError()
        ]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }


}
