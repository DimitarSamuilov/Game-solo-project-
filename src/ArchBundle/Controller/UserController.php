<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\BattleLog;
use ArchBundle\Entity\Role;
use ArchBundle\Entity\User;
use ArchBundle\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

            $doctrine = $this->getDoctrine();
            $user = $this->prepareUser($user);
            try {
                $em = $doctrine->getManager();
                $em->persist($user);
                $em->flush();
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('error', 'Username or email already taken!');
                return $this->render('user/register.html.twig', ['form' => $form->createView()]);
            }

            $this->get('services')->getBaseGeneration()->generateBases($this->getDoctrine(), $user);
            return $this->redirectToRoute("game_index");

        }
        if($form->getErrors(true,false)->getChildren()) {
            foreach ($form->getErrors(true, false)->getChildren()->current() as $item) {
                $this->get('session')->getFlashBag()->add('error', $item->getMessageTemplate());
            }
        }
        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param $user User
     * @return User
     */
    private function prepareUser($user)
    {
        $doctrine = $this->getDoctrine();
        $roleRepo = $doctrine->getRepository(Role::class);
        $userRole = $roleRepo->findOneBy(['name' => 'ROLE_USER']);

        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPassword());;

        $user->setPassword($password);
        $user->addRoles($userRole);
        return $user;
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/logged",name="login_redirect")
     */
    public function loggedAction()
    {
        $this->getBaseAction();
        $base = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        $this->get('services')->getStructureHelper()->structureUpgradeProcessing($base->getId(), $this->getDoctrine());
        $this->get('services')->getUnitHelper()->unitProductionProcessing($base->getId(), $this->getDoctrine());
        return $this->redirectToRoute("game_index");
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/profile",name="user_profile")
     */
    public function profilePageAction()
    {
        $battleLogs=$this->getDoctrine()->getRepository(BattleLog::class)->findBy(['user'=>$this->getUser()]);
        $base = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render('/user/profile.html.twig', ['base' => $base,'battleLogs'=>$battleLogs]);
    }
}
