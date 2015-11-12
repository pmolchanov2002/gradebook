<?php

// src/AppBundle/Controller/ReportAdminController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReportAdminController extends Controller {
	/**
	 * @Route("/admin/report/grades", name="app_admin_grades")
	 */
	public function display() {
		$gradeService = $this->get('GradeService');
		return $this->render ( 'report/grades.html.twig', $gradeService->obtainGrades ());
	}
	
	/**
	 * @Route("/admin/report/grades/print")
	 */
	public function displayPrint() {
		$gradeService = $this->get('GradeService');
		return $this->render ( 'report/gradesPrint.html.twig', $gradeService->obtainGrades ());
	}
}