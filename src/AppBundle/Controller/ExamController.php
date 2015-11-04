<?php

// src/AppBundle/Controller/ExamController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Exam;

class ExamController extends Controller
{

    private $displayRoute = 'app_exam_display';

    /**
     * @Route("/admin/exam/create")
     */
    public function create(Request $request)
    {
        $exam = new Exam();
        $form = $this->createFormBuilder($exam)
            ->add('name', 'text', array('label' => 'Exam name:'))
            ->add('examType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:ExamType',
            		'label' => 'Exam type: '
            ))
            ->add('ordinal', 'integer', array('label' => 'Sort order:'))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $exam = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($exam);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/exam.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/exam/edit/{id}")
     * @ParamConverter("exam", class="AppBundle:Exam")     
     */
    public function edit($exam, Request $request)
    {
        if(!isset($exam)) {
            return new Response("Exam not found");
        }
        $form = $this->createFormBuilder($exam)
            ->add('name', 'text', array('label' => 'Exam name:'))
            ->add('examType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:ExamType',
            		'label' => 'Exam type: '
            ))
            ->add('ordinal', 'integer', array('label' => 'Sort order:'))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $exam = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($exam);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/exam.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/exam/delete/{id}")
     * @ParamConverter("exam", class="AppBundle:Exam")
     */
    public function delete($exam)
    {
        if(!isset($exam)) {
            return new Response("Exam not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($exam);
        $em->flush();
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/exam")
     */       
    public function display() {
        $exams = $this->getDoctrine()
        ->getRepository('AppBundle:Exam')
        ->findAll();
        return $this->render('views/exam.html.twig',  array('exams' => $exams));
    }
}