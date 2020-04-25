<?php

// src/AppBundle/Controller/YearController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Year;

class YearController extends Controller
{

    private $displayRoute = 'app_year_display';

    /**
     * @Route("/admin/year/create")
     */
    public function create(Request $request)
    {
        $year = new Year();
        $form = $this->createFormBuilder($year)
            ->add('name', 'text', array('label' => 'Year of study:'))
            ->add('active', 'checkbox', array('label' => 'current year', 'required' => false ))
            ->add('lessonCount', 'integer', array('label' => 'Lessons per year:'))
            ->add('save', 'submit', array('label' => 'Create year of study'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $year = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($year);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/year.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/year/edit/{id}")
     * @ParamConverter("year", class="AppBundle:Year")     
     */
    public function edit($year, Request $request)
    {
        if(!isset($year)) {
            return new Response("Year not found");
        }
        $form = $this->createFormBuilder($year)
            ->add('name', 'text', array('label' => 'Year of study:'))
            ->add('active', 'checkbox', array('label' => 'current year', 'required' => false))
            ->add('lessonCount', 'integer', array('label' => 'Lessons per year:'))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $year = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($year);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/year.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/year/delete/{id}")
     * @ParamConverter("year", class="AppBundle:Year")
     */
    public function delete($year)
    {
        if(!isset($year)) {
            return new Response("Year not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($year);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/year")
     */       
    public function display() {
        $years = $this->getDoctrine()
        ->getRepository('AppBundle:Year')
        ->findAll();
        return $this->render('views/year.html.twig',  array('years' => $years));
    }
}