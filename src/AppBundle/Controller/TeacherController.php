<?php

// src/AppBundle/Controller/TeacherController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;


class TeacherController extends Controller
{

    private $displayRoute = 'app_teacher_display';

    /**
     * @Route("/admin/teacher/create")
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
            		'first_options' => array('label' => 'Password', 'required' => false),
            		'second_options' => array('label' => 'Repeat Password', 'required' => false),
            ))
           ->add('email', 'text', array('label' => 'Email:', 'required' => false))
           ->add('mobilePhone', 'text', array('label' => 'Mobile Phone:', 'required' => false))
           ->add('homePhone', 'text', array('label' => 'Home Phone:', 'required' => false))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            if($user->getPassword() != '') {
                $user->setPassword(md5($user->getPassword()));
            }
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneById(2);
            $user->addRole($roles);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/teacher.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/teacher/edit/{id}", name="app_teacher_edit")
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

        return $this->render('forms/teacher.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/teacher/delete/{id}")
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
     * @Route("/admin/teacher", name="app_teacher_display")
     */              
    public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select u from AppBundle\Entity\User u left join u.roles r where r.name='Teacher' and u.active=true");
        $users = $q->getResult();
        return $this->render('views/teacher.html.twig',  array('users' => $users));
    } 
    
    /**
     * @Route("/admin/teacher/lesson/{id}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function displaySchedule($user) {

        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl join cl.year y left join l.period p left join l.teacher t where t.id=:id and y.active=true order by p.ordinal")
        ->setParameter("id", $user->getId());   

        $lessons = $q->getResult();

        //print_r ($students);

        return $this->render('report/schedule.html.twig', array(
            "lessons" => $lessons,
        	'user' => $user
        ));
    }
    
    /**
     * @Route("/admin/teacher/lesson/{id}/print")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function displaySchedulePrint($user) {

        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl join cl.year y left join l.period p left join l.teacher t where t.id=:id and y.active=true order by p.ordinal")
        ->setParameter("id", $user->getId());   

        $lessons = $q->getResult();

        //print_r ($students);

        return $this->render('report/schedulePrint.html.twig', array(
            "lessons" => $lessons,
        	'user' => $user
        ));
    }
   
}