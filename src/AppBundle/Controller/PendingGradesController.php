<?php

// src/AppBundle/Controller/PendingGradesController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;
use Html2Pdf_HTML2PDF;

class PendingGradesController extends Controller {
	
	private $displayRoute = 'app_teacher_success';
	
	/**
	 * @Route("/admin/report/pendingGrades/exam")
	 */
	public function displayExams() {
		$exams = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findAll ();
		return $this->render ( 'report/pendingGrades/exam.html.twig', array (
				'exams' => $exams
		) );
	}
	
	/**
	 * @Route("/admin/report/pendingGrades/exam/{exam}")
	 * @ParamConverter("exam", class="AppBundle:Exam")
	 */
	public function displayPendingGrades($exam) {
		
		$em = $this->getDoctrine ()->getManager ();
		
		$q = $em->getRepository ( 'AppBundle:Lesson' )
			->createQueryBuilder ('l')
			->join ( 'l.classOfStudents', 'c' )
			->join ( 'c.year', 'y' )
			->where ( 'y.active = true' );
		
		$allLessons = $q->getQuery ()->execute ();
		
		$teachers = [];
		
		foreach($allLessons as $lesson) {
			$q = $em->getRepository ( 'AppBundle:GradeExam' )
			->createQueryBuilder ('g')
			->join ( 'g.class', 'c' )
			->join ( 'c.year', 'y' )
			->where ( 'y.active = true' )
			->andWhere( 'g.class = :class')
			->andWhere( 'g.course = :course')
			->andWhere( 'g.exam = :exam')
			->setParameter('class', $lesson->getClassOfStudents())
			->setParameter('course', $lesson->getCourse())
			->setParameter('exam', $exam);
			
			$grades = $q->getQuery ()->execute ();
			if(empty($grades)) {
				if(!in_array($lesson->getTeacher(), $teachers)) {
					$teachers[] = $lesson->getTeacher();
				}
			}
		}
		
		return $this->render ('report/pendingGrades/teacher.html.twig',
				array(
						"exam" => $exam,
						"teachers" => $teachers
				));
	}
	
	/**
	 * @Route("/admin/report/pendingGrades/mail/exam/{exam}/teacher/{teacher}")
	 * @ParamConverter("exam", class="AppBundle:Exam")
	 * @ParamConverter("teacher", class="AppBundle:User")
	 */
	public function sendNotification($exam, $teacher, Request $request) {
		
		$teachers = array();
		$teachers[] = $teacher->__toString();
		$session = $request->getSession();
		$session->set('teachers', $teachers);
		$session->set('exam', $exam->getName());
		
		$this->sendEmail($teacher, $exam);
		return $this->redirectToRoute($this->displayRoute);
	}
	
	private function sendEmail($user, $exam) {
		$message = \Swift_Message::newInstance()
		->setSubject($exam->getName().': Grades are pending for St. Sergius School students')
		->setFrom('pavel@stsergiuslc.com')
		->setTo($user->getEmail())
		->setBody(
				$this->renderView(
						'mail/pendingGrades.html.twig',
						array(
								'user' => $user,
								'exam' => $exam
						)
				),
				'text/html'
		)
		->addPart(
				$this->renderView(
						'mail/pendingGrades.html.twig',
						array('user' => $user,
								'exam' => $exam
						)
				),
				'text/plain'
		);
		$this->get('mailer')->send($message);
		return;
	}
}