<?php

// src/AppBundle/Controller/ReportAdminController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;
use Html2Pdf_HTML2PDF;

class ReportAdminController extends Controller {
	/**
	 * @Route("/admin/report/grades", name="app_admin_grades")
	 */
	public function display() {
		return $this->displayGrades ( 'report/grades.html.twig' );
	}
	
	/**
	 * @Route("/admin/report/grades/print")
	 */
	public function displayPrint() {
		return $this->displayGrades ( 'report/gradesPrint.html.twig' );
	}
	
	public function displayGrades($reportName) {
		$em = $this->getDoctrine ()->getManager ();
		
		$q = $em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addOrderBy ( 'c.ordinal' )
		->addOrderBy ( 'g.student' )
		->setParameter ( 'code', 'Course' );
		
		$examStudents = $q->getQuery ()->execute ();
		
		$q = $em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.course', 'co' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addGroupBy ( 'g.course' )
		->addOrderBy ( 'co.name' )
		->setParameter ( 'code', 'Course' );
		
		$gradeCourses = $q->getQuery ()->execute ();
		
		$cachedGradeCourses = [];
		
		foreach($gradeCourses as $examGrade) {
			$compoundId = array(
					$examGrade->getStudent()->getId(),
					$examGrade->getCourse()->getId(),
					$examGrade->getClass()->getId(),
					$examGrade->getExam()->getId()
			);
			$id = join('-', $compoundId);
			$cachedGradeCourses[$id] = $examGrade;
		}
		
		$q = $em->getRepository ( 'AppBundle:Exam' )
		->createQueryBuilder ( 'e' );
		
		$allExams = $q->getQuery ()->execute ();
		
		$q = $em->getRepository ( 'AppBundle:Exam' )
		->createQueryBuilder ( 'e' )
		->join ( 'e.examType', 'et' )
		->where ( 'et.code = :code' )
		->setParameter ( 'code', 'MidTerm' );
		
		$exams = $q->getQuery ()->execute ();
		
		$diligence = $this->avgGrade ( 'Diligence' );
		$discipline = $this->avgGrade ( 'Discipline' );
		
		return $this->render ( $reportName, array (
				'examStudents' => $examStudents,
				'examCourses' => $cachedGradeCourses,
				'exams' => $exams,
				'allExams' => $allExams,
				'diligence' => $diligence,
				'discipline' => $discipline 
		) );
	}
	
	private function avgGrade($gradeTypeCode) {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->select ( "gt.name as name, e.id as examId, s.id as studentId, c.id as classId, avg(g.grade) as grade" )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.exam', 'e' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addGroupBy ( 'g.exam' )
		->addOrderBy ( 'g.class' )
		->addOrderBy ( 'g.student' )
		->addOrderBy ( 'g.exam' )
		->setParameter ( 'code', $gradeTypeCode );
		
		return $q->getQuery ()->execute ();
	}
}