<?php

// src/AppBundle/Controller/ReportFinalController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReportFinalController extends Controller {
	/**
	 * @Route("/admin/report/final", name="app_final_grades")
	 */
	public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select gr from AppBundle\Entity\FinalGrade gr");
        $results = $q->getResult();
		return $this->render ( 'report/finalGrades.html.twig', array('results' => $results));
	}
	
	/**
	 * @Route("/admin/report/final/print")
	 */
	public function displayPrint() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select gr from AppBundle\Entity\FinalGrade gr");
        $results = $q->getResult();
		return $this->render ( 'report/finalGradesPrint.html.twig', array('results' => $results));
	}
}