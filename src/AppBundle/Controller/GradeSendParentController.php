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

class GradeSendParentController extends Controller {
	private $displayRoute = 'app_mail_parent_success';
	

	/**
	 * @Route("/admin/mail/parent")
	 */
	public function display_parents(Request $request) {
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Parents: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.students','s')
					->join('s.classes','c')
					->join('c.year', 'y')
					->join('u.roles', 'r')
					->where('u.active=true')
					->andWhere('r.role=:role')
					->andWhere('u.email is not NULL')
					->andWhere('y.active=true')
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
		$mailer = $this->get('mailer');
		$mailer->send($message);
		$spool = $mailer->getTransport()->getSpool();
		$transport = $container->get('swiftmailer.transport.real');
		$spool->flushQueue($transport);
		return;
	}

}