<?php

// src/AppBundle/Controller/DayController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Day;

class DayController extends Controller
{

    private $displayRoute = 'app_day_display';

    /**
     * @Route("/admin/day/create")
     */
    public function create(Request $request)
    {
        $day = new Day();
        $form = $this->createFormBuilder($day)
            ->add('name', 'text', array('label' => 'Name:'))
            ->add('date', 'date', array('label' => 'Calendar date:', 'years' => range(date('Y'), date('Y') -3)))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $day = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $year = $em->createQuery('select y from AppBundle:Year y where y.active=true')->getSingleResult();
            $day->setYear($year);
            $em->persist($day);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/day.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/day/edit/{id}")
     * @ParamConverter("day", class="AppBundle:Day")     
     */
    public function edit($day, Request $request)
    {
        if(!isset($day)) {
            return new Response("Day not found");
        }
        $form = $this->createFormBuilder($day)
	        ->add('name', 'text', array('label' => 'Name:'))
	        ->add('date', 'date', array('label' => 'Calendar date:', 'years' => range(date('Y'), date('Y') -100)))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $day = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($day);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/day.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/day/delete/{id}")
     * @ParamConverter("day", class="AppBundle:Day")
     */
    public function delete($day)
    {
        if(!isset($day)) {
            return new Response("Day not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($day);
        $em->flush();
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/day")
     */       
    public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select d from AppBundle\Entity\Day d left join d.year y where y.active=true order by d.date asc");
        $days = $q->getResult();
        return $this->render('views/day.html.twig',  array('days' => $days));
    }
}