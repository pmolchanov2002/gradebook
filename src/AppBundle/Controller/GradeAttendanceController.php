<?php

// src/AppBundle/Controller/GradeAttendanceController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeAttendance;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormError;

class GradeAttendanceController extends Controller {
	private $displayRoute = 'app_exam_display';
	
	/**
	 * @Route("/admin/attendance/exam")
	 */
	public function displayExams() {
		$exams = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findAll ();
		return $this->render ( 'views/attendanceExam.html.twig', array (
				'exams' => $exams 
		) );
	}

	/**
	 * @Route("/admin/attendance/exam/{examId}")
	 */
	public function displayStudents($examId) {
		$exam = $this->getExam ( $examId );
		if (! isset ( $exam )) {
			return new Response ( "Exam not found" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		
		$q = $em->getRepository ( 'AppBundle:Year' )
		->createQueryBuilder ( 'y' )
		->where( 'y.active=true');
		
		$year = $q->getQuery()->getSingleResult();
		
		$q = $em->createQuery ( "select u from AppBundle:User u join u.classes cl join cl.year y where u.active=true and y.active=true order by cl.ordinal, u.lastName");
		
		$students = $q->getResult ();
		
		$grades = [];
		$allCorrect = true;
		
		$post = Request::createFromGlobals ();
		
		if ($post->request->has ( 'submit' )) {
			$maxGrade = $post->request->get ( 'maxGrade' );
			foreach ( $students as $student ) {
				if ($allCorrect) {
					$grade = $post->request->get ( 'grade_'. $student->getId () );
					
					if (isset ( $grade )) {
						$gradeAttendance = new GradeAttendance ();
						$gradeAttendance->setExam ( $exam );
						$gradeAttendance->setStudent ( $student );
						$gradeAttendance->setYear ( $year );
						if ($maxGrade >= 1) {
							$gradeAttendance->setMaxGrade ( $maxGrade );
						} else {
							$allCorrect = false;
							break;
						}
						if ($grade <= $maxGrade && $grade >= 1) {
							$gradeAttendance->setGrade ( $grade );
						} else {
							$allCorrect = false;
							break;
						}
						$grades [] = $gradeAttendance;
					}
				}
			}
			
			if ($allCorrect) {
				foreach ( $grades as $grade ) {
					$em->persist ( $grade );
				}
				$em->flush ();
			}
		}
		
		$q = $em->getRepository ( 'AppBundle:GradeAttendance' )
		->createQueryBuilder ( 'g' )
		->join('g.year', 'y')
		->where ( 'g.exam = :examId' )
		->andWhere( 'y.active=true')
		->setParameter ( "examId", $examId );

		$grades = $q->getQuery ()->execute();
		
		$gradesAttendance = array ();
		
		$enableSubmit = false;
		
		foreach ( $students as $student ) {
			$gradeAttendance = new GradeAttendance ();
			$gradeAttendance->setExam ( $exam );
			$gradeAttendance->setStudent ( $student );
			$gradeAttendance->setYear ( $year );
			
			$foundGrade = false;
			foreach ( $grades as $grade ) {
				if ($grade->getStudent()->getId() == $student->getId ()) {
					$gradeAttendance->setGrade ( $grade->getGrade () );
					$gradeAttendance->setMaxGrade ( $grade->getMaxGrade () );
					$foundGrade = true;
				}
			}
			if(!$foundGrade && !$enableSubmit) {
				$enableSubmit = true;
			}
			$gradesAttendance [] = $gradeAttendance;
		}
		
		return $this->render ( 'views/attendanceGrades.html.twig', array (
				'gradesAttendance' => $gradesAttendance,
				'examId' => $examId,
				'examName' => $exam->getName (),
				'enableSubmit' => $enableSubmit
		) );
	}
	
	private function getExam($examId) {
		$exam = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findOneById ( $examId );
		return $exam;
	}
}