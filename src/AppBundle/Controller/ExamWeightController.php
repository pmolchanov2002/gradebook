<?php

// src/AppBundle/Controller/ExamController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\ExamWeight;
use AppBundle\Entity\ExamType;

class ExamWeightController extends Controller
{

    private $displayRoute = 'app_examweight_display';

    /**
     * @Route("/admin/examweight/create")
     */
    public function create(Request $request)
    {
        $examWeight = new ExamWeight();
        $form = $this->createFormBuilder($examWeight)
            ->add('examType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:ExamType',
            		'label' => 'Exam type: ',
            		'choice_label' => 'name'
            ))
            ->add('gradeType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:GradeType',
            		'label' => 'Grade type: ',
            		'choice_label' => 'name'
            ))
            ->add('weight', 'text', array('label' => 'Weight:'))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $examWeight = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($examWeight);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/examWeight.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/examweight/edit/{examTypeId}/{gradeTypeId}")
     * @ParamConverter("examWeight", class="AppBundle:ExamWeight", options={"mapping": {"examTypeId": "examType", "gradeTypeId": "gradeType"}})     
     */
    public function edit($examWeight, Request $request)
    {
        if(!isset($examWeight)) {
            return new Response("Exam not found");
        }
        $form = $this->createFormBuilder($examWeight)
            ->add('examType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:ExamType',
            		'label' => 'Exam type: ',
            		'choice_label' => 'name'
            ))
            ->add('gradeType', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:GradeType',
            		'label' => 'Grade type: ',
            		'choice_label' => 'name'
            ))
            ->add('weight', 'text', array('label' => 'Weight:'))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $examWeight = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($examWeight);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/examWeight.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/examweight/delete/{examTypeId}/{gradeTypeId}")
     * @ParamConverter("examWeight", class="AppBundle:ExamWeight", options={"mapping": {"examTypeId": "examType", "gradeTypeId": "gradeType"}})
     */
    public function delete($examWeight)
    {
        if(!isset($examWeight)) {
            return new Response("Exam weight not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($examWeight);
        $em->flush();
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/examweight")
     */       
    public function display() {
        $examWeights = $this->getDoctrine()
        ->getRepository('AppBundle:ExamWeight')
        ->findAll();
        return $this->render('views/examWeight.html.twig',  array('examWeights' => $examWeights));
    }
}