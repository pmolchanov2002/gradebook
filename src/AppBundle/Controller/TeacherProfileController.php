<?php

// src/AppBundle/Controller/TeacherProfileController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;


class TeacherProfileController extends Controller
{

    private $displayRoute = 'teacher_homepage';

    /**
     * @Route("/teacher/profile", name="app_teacher_profile")     
     */
    public function edit(Request $request)
    {
    	$id = $this->get('security.context')->getToken()->getUser()->getId();
    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository("AppBundle:User")->find($id);
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
           	->add('mobilePhone', 'text', array('label' => 'Mobile Phone:', 'required' => false))
           	->add('homePhone', 'text', array('label' => 'Home Phone:', 'required' => false))
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

        return $this->render('forms/teacherProfile.html.twig', array(
            'form' => $form->createView(),
        	'user' => $user
        ));
    }
}