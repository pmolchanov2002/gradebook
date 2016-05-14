<?php

// src/AppBundle/Controller/ClassController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\Year;
use AppBundle\Entity\ClassOfStudents;


class ClassController extends Controller
{

    private $displayRoute = 'app_class_display';

    /**
     * @Route("/admin/class/create")
     */
    public function create(Request $request)
    {
        $classOfStudents = new ClassOfStudents();
        $form = $this->createFormBuilder($classOfStudents)
            ->add('name', 'text', array('label' => 'Name of the class:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('ordinal', 'integer', array('label' => 'Order number:'))
            ->add('save', 'submit', array('label' => 'Create class of students'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $classOfStudents = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $year = $em->createQuery('select y from AppBundle:Year y where y.active=true')->getSingleResult();
            $classOfStudents->setYear($year);
            $em->persist($classOfStudents);
            $em->flush();
            //return new Response("Class of students ".$classOfStudents->getName()." was added");
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/class.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/class/edit/{id}")
     * @ParamConverter("classOfStudents", class="AppBundle:ClassOfStudents")     
     */
    public function edit($classOfStudents, Request $request)
    {
        if(!isset($classOfStudents)) {
            return new Response("Class not found");
        }
        $form = $this->createFormBuilder($classOfStudents)
            ->add('name', 'text', array('label' => 'Name of the class:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))            
            ->add('ordinal', 'integer', array('label' => 'Order number:'))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $classOfStudents = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $year = $em->createQuery('select y from AppBundle:Year y where y.active=true')->getSingleResult();
            $classOfStudents->setYear($year);
            $em->persist($classOfStudents);
            $em->flush();
            //return new Response("Class of students ".$classOfStudents->getName()." was saved");
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/class.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/class/delete/{id}")
     * @ParamConverter("classOfStudents", class="AppBundle:ClassOfStudents")
     */
    public function delete($classOfStudents)
    {
        if(!isset($classOfStudents)) {
            return new Response("Class not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($classOfStudents);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/class")
     */       
    public function display() {
        $classesOfStudents = $this->getDoctrine()
        ->getRepository('AppBundle:ClassOfStudents')
        ->createQueryBuilder('cl')
        ->leftJoin('cl.year', 'y')
        ->where('y.active=true')
        ->getQuery()->execute();
        return $this->render('views/classesofstudents.html.twig',  array('classesOfStudents' => $classesOfStudents));
    }

    /**
     * @Route("/admin/class/students/{id}")
     * @ParamConverter("classOfStudents", class="AppBundle:ClassOfStudents")     
     */       
    public function displayStudents($classOfStudents) {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select u from AppBundle\Entity\User u left join u.classes c join c.year y where y.active=true and c.id=:id")
        ->setParameter('id', $classOfStudents->getId());

        $users = $q->getResult();
        return $this->render('views/studentsinclass.html.twig',  array('class' => $classOfStudents, 'users' => $users));
    }     
}