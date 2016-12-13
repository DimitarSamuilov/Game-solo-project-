<?php

namespace ArchBundle\Controller\Admin;

use ArchBundle\Entity\Role;
use ArchBundle\Entity\User;
use ArchBundle\Form\UserEditType;
use ArchBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin/users")
 * Class UserController
 * @package ArchBundle\Controller\Admin
 */
class UserController extends Controller
{
    /**
     * @Route("/" ,name="admin_users")
     * @return Response
     */
    public function listUsers()
    {
        $users=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render("admin/user/list.html.twig",['users'=>$users]);
    }

    /**
     * @Route("/edit/{id}",name="admin_user_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editUser($id,Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        if($user===null){
            return $this->redirect('admin_users');
        }

        $originalPassword=$user->getPassword();
        $form=$this->createForm(UserEditType::class,$user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rolesRequest=$user->getRoles();
            $rolesRepository=$this->getDoctrine()->getRepository(Role::class);
            $roles=[];

            foreach ($rolesRequest as $roleName){
                $roles[]=$rolesRepository->findOneBy(['name'=>$roleName]);
            }
            $user->setRoles($roles);

            if($user->getPassword()){
                $password=$this->get('security.password_encoder')->encodePassword($user,$user->getPassword());
                $user->setPassword($password);
            }else{
                $user->setPassword($originalPassword);
            }
            $em=$this->getDoctrine()->getEntityManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_users');

        }
        return $this->render('admin/user/edit.html.twig',['user'=>$user,'form'=>$form->createView()]);
    }

    /**
     * @Route("/delete/{id}",name="admin_user_delete")
     * @param $id
     * @param Request $request
     * @return Response
     *
     */
    public function deleteUserAction($id,Request $request)
    {
        $user=$this->getDoctrine()->getRepository(User::class)->find($id);

        if($user==null){
            return $this->redirectToRoute('admin_users');
        }

        $form=$this->createForm(UserEditType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/user/delete.html.twig',['user'=>$user,'form'=>$form->createView()]);
    }
}
