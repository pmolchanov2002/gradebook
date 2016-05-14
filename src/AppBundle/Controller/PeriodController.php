<?php

// src/AppBundle/Controller/PeriodController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Period;

class PeriodController extends Controller
{

    private $displayRoute = 'app_period_display';

    /**
     * @Route("/admin/period/create")
     */
    public function create(Request $request)
    {
        $period = new Period();
        $form = $this->createFormBuilder($period)
            ->add('name', 'text', array('label' => 'Period name:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('startTime', 'time', array('label' => 'Start time:'))
            ->add('endTime', 'time', array('label' => 'End time:'))
            ->add('ordinal', 'integer', array('label' => 'Sort order:'))            
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $period = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($period);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/period.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/period/edit/{id}")
     * @ParamConverter("period", class="AppBundle:Period")     
     */
    public function edit($period, Request $request)
    {
        if(!isset($period)) {
            return new Response("Period not found");
        }
        $form = $this->createFormBuilder($period)
            ->add('name', 'text', array('label' => 'Period name:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('startTime', 'time', array('label' => 'Start time:'))
            ->add('endTime', 'time', array('label' => 'End time:'))
            ->add('ordinal', 'integer', array('label' => 'Sort order:'))   
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $period = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($period);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/period.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/period/delete/{id}")
     * @ParamConverter("period", class="AppBundle:Period")
     */
    public function delete($period)
    {
        if(!isset($period)) {
            return new Response("Period not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($period);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/period")
     */       
    public function display() {
        $periods = $this->getDoctrine()
        ->getRepository('AppBundle:Period')
        ->findAll();
        return $this->render('views/period.html.twig',  array('periods' => $periods));
    }
}