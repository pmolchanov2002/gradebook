<?php

// src/AppBundle/Controller/CourseController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Course;

class CourseController extends Controller
{

    private $displayRoute = 'app_course_display';

    /**
     * @Route("/admin/course/create")
     */
    public function create(Request $request)
    {
        $course = new Course();
        $form = $this->createFormBuilder($course)
            ->add('name', 'text', array('label' => 'Course name:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $year = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($year);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/course.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/course/edit/{id}")
     * @ParamConverter("course", class="AppBundle:Course")     
     */
    public function edit($course, Request $request)
    {
        if(!isset($course)) {
            return new Response("Course not found");
        }
        $form = $this->createFormBuilder($course)
            ->add('name', 'text', array('label' => 'Course name:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $course = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/course.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/course/delete/{id}")
     * @ParamConverter("course", class="AppBundle:Course")
     */
    public function delete($course)
    {
        if(!isset($course)) {
            return new Response("Course not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($course);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/course")
     */       
    public function display() {
        $courses = $this->getDoctrine()
        ->getRepository('AppBundle:Course')
        ->findAll();
        return $this->render('views/course.html.twig',  array('courses' => $courses));
    }
}