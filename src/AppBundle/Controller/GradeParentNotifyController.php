<?php

// src/AppBundle/Controller/GradeParentNotifyController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\Notification;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Model\GradeQuery;

class GradeParentNotifyController extends Controller {
	private $displayRoute = 'app_mail_parent_success';
	
	/**
	 * @Route("/admin/mail/parent")
	 */	
	public function displayExams() {
		$exams = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findAll ();
		return $this->render ( 'views/parent/mail/exam.html.twig', array (
				'exams' => $exams
		) );
	}
	
	/**
	 * @Route("/admin/mail/parent/exam/{id}")
	 * @ParamConverter("exam", class="AppBundle:Exam")
	 */
	public function display_parents($exam, Request $request) {
		
		$notification = new Notification();
		$notification->setExam($exam);
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Parents: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->where('u.active=true')
					->andWhere('r.role=:role')
					->orderBy('u.lastName', 'ASC')
					->setParameter('role', 'ROLE_PARENT');
				}
		))
		->add('send', 'submit', array('label' => 'Send'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$notification = $form->getData();
			$teachers = array();
			foreach ($notification->getUsers() as $parent) {
				$this->sendEmail($parent);
				$parents[] = $parent->__toString();
			}
			
			$session = $request->getSession();
			$session->set('parents', $parents);
			$session->set('exam', $exam->getName());
			
			return $this->redirectToRoute($this->displayRoute);
		}
		
		return $this->render('forms/mailParents.html.twig', array(
				'form' => $form->createView()
		));
    
	}
	
	/**
	 * @Route("/admin/mail/parent/success", name="app_mail_parent_success")
	 */		
	public function display_success(Request $request) {
		$session = $request->getSession();
		$parents = $session->get('parents');
		$exam = $session->get('exam');
		
		return $this->render(
				'parent/mail/success.html.twig',
				array(
						'parents' => $parents
				)
		);
	}
	
	
	
	private function sendEmail($user) {
		$gradeService = $this->get('GradeService');
		$query = new GradeQuery();
		$query->setParentId($user->getId());
		
		$message = \Swift_Message::newInstance()
		->setSubject('St. Sergius School. Grades.')
		->setFrom('pavel@stsergiuslc.com')
		->setTo($user->getEmail())
		->setBody(
				$this->renderView(
						'mail/gradesReport.html.twig',
						array(
							'gradeResult' => $gradeService->obtainGrades ($query),
							'user' => $user
						)
				),
				'text/html'
		);
		$this->get('mailer')->send($message);
		return;
	}

}