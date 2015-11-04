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
	
	/**
	 * @Route("/admin/report/grades/pdf")
	 */
	public function displayPdf() {
		$html = $this->displayGrades ( 'report/gradesPdf.html.twig' );
		$html2pdf = $this->get ('html2pdf_factory')->create ( 'P', 'A4', 'en' );
		//$html2pdf->setDefaultFont ( 'arialunicid0' );
		 $html2pdf->setDefaultFont('freeserif');
		// real : utilise la taille réelle
		$html2pdf->pdf->SetDisplayMode ( 'real' );
		// writeHTML va tout simplement prendre la vue stocker dans la variable $html pour la convertir en format PDF
		$html2pdf->writeHTML ( $html );
		// Output envoit le document PDF au navigateur internet
		return new Response ( $html2pdf->Output ( 'grades.pdf' ), 200, array (
				'Content-Type' => 'application/pdf' 
		) );
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
			$id = $examGrade->getStudent()->getId();
			$id .= '-';
			$id .= $examGrade->getCourse()->getId();
			$id .= '-';
			$id .= $examGrade->getClass()->getId();
			$id .= '-';
			$id .= $examGrade->getExam()->getId();
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