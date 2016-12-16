<?php

namespace ArchBundle\Controller;

use ArchBundle\Entity\Base;
use ArchBundle\Entity\BattleLog;
use ArchBundle\Entity\Role;
use ArchBundle\Entity\User;
use ArchBundle\Entity\UserMessage;
use ArchBundle\Form\UserMessageFormType;
use ArchBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
        if ($form->getErrors(true, false)->getChildren()) {
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
        $battleLogs = $this->getDoctrine()->getRepository(BattleLog::class)->findBy(['user' => $this->getUser()]);
        $base = $this->getDoctrine()->getRepository(Base::class)->find($this->getBaseAction());
        return $this->render('/user/profile.html.twig', ['base' => $base, 'battleLogs' => $battleLogs]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("users/messageForm/{username}", name="user_send_message_form")
     */
    public function sendMessageViewAction($username = null, Request $request)
    {
        $message = new UserMessage();
        if ($username !== null) {
            $message->setRecipient($username);
        }
        $form = $this->createForm(UserMessageFormType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $recipient = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $message->getRecipient()]);
            if ($recipient == null) {
                $this->get('session')->getFlashBag()->add('error', 'no user with that username');
                return $this->render('user/messageForm.html.twig', ['form' => $form->createView()]);
            }
            $this->sendMessage($message, $recipient);
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('user/messageForm.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/user/unreadMessages",name="users_unread_messages")
     */
    public function viewUnreadMessagesAction()
    {
        $currentUser = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy(['receiver' => $currentUser]);
        foreach ($messages as $message) {
            $message->setSend($message->getSend()->format('F j, Y, g:i a'));
        }
        return $this->render('user/renderUnreadMessages.html.twig', ['messages' => $messages]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/user/readMessages",name="users_read_messages")
     */
    public function viewReadMessages()
    {
        $currentUser = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy(['receiver' => $currentUser]);
        foreach ($messages as $message) {
            $message->setSend($message->getSend()->format('F j, Y, g:i a'));
        }
        return $this->render('user/renderReadMessages.html.twig', ['messages' => $messages]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/allMessagesRead",name="mark_all_as_read")
     */
    public function marAllAsRead()
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $messages = $this->getDoctrine()->getRepository(UserMessage::class)->findBy(['receiver' => $currentUser]);
        foreach ($messages as $message) {
            $message->setIsRead(true);
            $em->persist($message);
            $em->flush();
            /* $em->remove($message);
             $em->flush();*/
        }
        return $this->redirectToRoute('users_unread_messages');
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $messageId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/user/markAsRead/{messageId}",name="mark_as_read")
     */
    public function markMessageAsReadAction($messageId)
    {
        $message = $this->getDoctrine()->getRepository(UserMessage::class)->find($messageId);
        if ($message === null) {
            $this->get('session')->getFlashBag()->add('error', 'no such message to delete');
            return $this->redirectToRoute('users_unread_messages');
        }
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        return $this->redirectToRoute('users_unread_messages');
    }

    /**
     *
     * @param $message UserMessage
     * @param $receivingUser User
     */
    private function sendMessage($message, $receivingUser)
    {
        /**
         * @var $currentUser User
         */
        $currentUser = $this->getUser();
        $message->setReceiver($receivingUser);
        $message->setSender($currentUser);
        $message->setIsRead(false);
        $message->setSend(new \DateTime());
        $receivingUser->addReceivedMessages($message);
        $currentUser->addSendMessages($message);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        $em->persist($receivingUser);
        $em->persist($currentUser);
        $em->flush();
    }
}
