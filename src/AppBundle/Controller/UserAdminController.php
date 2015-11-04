<?php

// src/AppBundle/Controller/UserAdminController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;


class UserAdminController extends Controller
{

    private $displayRoute = 'app_admin_display';

    /**
     * @Route("/admin/admin/create")
     */
    public function create(Request $request)
    {
        $user = new User();
        $user->setActive(true);
        $form = $this->createFormBuilder($user)
        	->add('lastName', 'text', array('label' => 'Last Name:'))
        	->add('firstName', 'text', array('label' => 'First Name:'))
            ->add('username', 'text', array('label' => 'Login:'))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Password fields do not match',
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
           ->add('email', 'text', array('label' => 'Email:', 'required' => false))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            if($user->getPassword() != '') {
                $user->setPassword(md5($user->getPassword()));
            }
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneById(1);
            $user->addRole($roles);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/admin.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/admin/edit/{id}")
     * @ParamConverter("user", class="AppBundle:User")     
     */
    public function edit($user, Request $request)
    {
        if(!isset($user)) {
            return new Response("User not found");
        }
        
        $old_password = $user->getPassword();
        
        $form = $this->createFormBuilder($user)
        	->add('lastName', 'text', array('label' => 'Last Name:'))
        	->add('firstName', 'text', array('label' => 'First Name:'))
            ->add('username', 'text', array('label' => 'Login:'))
            ->add('password', 'repeated', array(
            		'type' => 'password',
            		'required' => false,
            		'invalid_message' => 'Password fields do not match',
            		'first_options' => array('label' => 'Password', 'required' => false),
            		'second_options' => array('label' => 'Repeat Password', 'required' => false),
            ))
           	->add('email', 'text', array('label' => 'Email:', 'required' => false))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            if($user->getPassword() != $old_password && $user->getPassword() != '') {
                $user->setPassword(md5($user->getPassword()));
            } else {
                $user->setPassword($old_password);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/admin.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/admin/delete/{id}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function delete($user)
    {
        if(!isset($user)) {
            return new Response("User not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

    /**
     * @Route("/admin/admin", name="app_admin_display")
     */       
    public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select u from AppBundle\Entity\User u left join u.roles r where r.name='Admin'");
        $users = $q->getResult();
        return $this->render('views/admin.html.twig',  array('users' => $users));
    }
   
}