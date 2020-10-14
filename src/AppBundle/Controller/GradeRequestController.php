<?php

// src/AppBundle/Controller/GradeRequestController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\Notification;
use Symfony\Component\HttpFoundation\Session\Session;


class GradeRequestController extends Controller {
	private $displayRoute = 'app_teacher_success';
	
	/**
	 * @Route("/admin/mail/exam")
	 */	
	public function displayExams() {
		$exams = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findAll ();
		return $this->render ( 'views/teacher/mail/exam.html.twig', array (
				'exams' => $exams
		) );
	}
	
	/**
	 * @Route("/admin/mail/exam/{id}")
	 * @ParamConverter("exam", class="AppBundle:Exam")
	 */
	public function display_teachers($exam, Request $request) {
		
		$notification = new Notification();
		$notification->setExam($exam);
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Teachers: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.lessons', 'l')
					->join ( 'l.classOfStudents', 'c' )
					->join('c.year', 'y')
					->where('y.active=true')
					->orderBy('u.lastName', 'ASC')
					->groupBy('l.teacher');
				}
		))
		->add('send', 'submit', array('label' => 'Send'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$notification = $form->getData();
			$teachers = array();
			foreach ($notification->getUsers() as $teacher) {
				$this->sendEmail($teacher, $exam, null);
				$teachers[] = $teacher->__toString();
			}
			
			$session = $request->getSession();
			$session->set('teachers', $teachers);
			$session->set('exam', $exam->getName());
			
			return $this->redirectToRoute($this->displayRoute);
		}
		
		return $this->render('forms/mailTeachers.html.twig', array(
				'form' => $form->createView()
		));
    
	}
	
	/**
	 * @Route("/admin/mail/success", name="app_teacher_success")
	 */		
	public function display_success(Request $request) {
		$session = $request->getSession();
		$teachers = $session->get('teachers');
		$exam = $session->get('exam');
		
		return $this->render(
				'teacher/mail/success.html.twig',
				array(
						'teachers' => $teachers,
						'exam'=> $exam
				)
		);
	}
	
	
	
	private function sendEmail($user, $exam, $lessons) {
		$message = \Swift_Message::newInstance()
		->setSubject($exam->getName().': Please submit grades for St. Sergius School students')
		->setFrom($this->getParameter('mailer_user'))
		->setTo(!empty($user->getRoutingEmail()) ? $user->getRoutingEmail() : $user->getEmail())		
		->setBody(
				$this->renderView(
						'mail/gradesRequest.html.twig',
						array(
							'user' => $user,
							'lessons' => $lessons,
							'exam' => $exam
						)
				),
				'text/html'
		)
		->addPart(
				$this->renderView(
						'mail/gradesRequest.txt.twig',
						array('user' => $user,
							'lessons' => $lessons,
							'exam' => $exam
						)
				),
				'text/plain'
		);
		$this->get('mailer')->send($message);
		return;
	}

}