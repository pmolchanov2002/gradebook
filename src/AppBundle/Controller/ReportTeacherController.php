<?php

// src/AppBundle/Controller/ReportTeacherController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Model\GradeQuery;

class ReportTeacherController extends Controller
{
    /**
     * @Route("/teacher/report/grades")
     */  
    public function displayGrades() {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setTeacherId($this->get('security.context')->getToken()->getUser()->getId());
    	return $this->render ( 'report/teacher/grades.html.twig', array('gradeResult' => $gradeService->obtainGrades ($query)));
    }
}