<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\Building;
use ArchBundle\Entity\Role;
use ArchBundle\Entity\User;
use ArchBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package ArchBundle\Controller
 *
 */
class UserController extends BaseHelperController
{
    /**
     * @Route("/user/register",name="user_register")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !empty($form->getData()->getPassword())) {
            $doctrine=$this->getDoctrine();
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPassword());;
            $user->setPassword($password);
            $roleRepo = $doctrine->getRepository(Role::class);
            $userRole = $roleRepo->findOneBy(['name' => 'ROLE_USER']);
            $user->addRoles($userRole);
            $em = $doctrine->getEntityManager();
            $em->persist($user);
            $em->flush();
            $this->get('services')->getBaseGeneration()->generateBases($this->getDoctrine(),$user);
            return $this->redirectToRoute("game_index");
        }
        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/logged",name="login_redirect")
     */
    public function loggedAction()
    {
        $this->getBaseAction();
        return $this->redirectToRoute("game_index");
    }
}
