<?php

// src/AppBundle/Controller/ParentMessageController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\Notification;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class StudentMessageController extends Controller {
	
	private $displayRoute = 'app_mail_student_schedule_success';
	
	/**
	 * @Route("/admin/message/student")
	 */
	public function displayMessageForm(Request $request) {
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Recepients: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->where('u.active=true')
					->andWhere('r.role=:role')
					->andWhere('u.email is not NULL')
					->andWhere("u.email not like '%@stsergiuslc.com'")
					->orderBy('u.lastName', 'ASC')
					->setParameter('role', 'ROLE_STUDENT');
				}
		))
		->add('send', 'submit', array('label' => 'Send'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$notification = $form->getData();
			$recipients = array();
			foreach ($notification->getUsers() as $recipient) {
				$this->sendEmail($notification, $recipient);
				$recipients[] = $recipient->__toString();
			}
			
			$session = $request->getSession();
			$session->set('recipients', $recipients);
		
			return $this->redirectToRoute($this->displayRoute);
		}
		
		return $this->render('forms/message/mail.html.twig', array(
				'form' => $form->createView()
		));
    
	}
	
	/**
	 * @Route("/admin/mail/message/success", name="app_mail_student_schedule_success")
	 */		
	public function display_success(Request $request) {
		$session = $request->getSession();
		$recipients = $session->get('recipients');
		
		return $this->render(
				'forms/message/success.html.twig',
				array(
						'recipients' => $recipients
				)
		);
	}
	
	private function sendEmail($notification, $user) {
		
		$em = $this->getDoctrine()->getManager();

		// Get schedule from the database

		$studentLessons = array();


		$q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl left join cl.students s left join cl.year y left join l.period p where s.id=:id and y.active=true order by p.ordinal")
		->setParameter("id", $user->getId());
		$lessons = $q->getResult();
		$studentLessons[] = array(
			"student" => $user,
			"lessons" => $lessons
		);
		
		// Create Russian message

		$notification->setMessage($this->renderView('mail/scheduleStudent.html.twig', array(
				"studentLessons" => $studentLessons,
				'user' => $user
		)));

		// Create English message
		
		$notification->setEnglishMessage($this->renderView('mail/scheduleStudentEnglish.html.twig', array(
				"studentLessons" => $studentLessons,
				'user' => $user
		)));
		
		// Create message to send 
		
		$body = $this->renderView(
				'mail/studentMessage.html.twig',
				array(
						'notification' => $notification,
						'user' => $user
		));

		$message = \Swift_Message::newInstance()
		->setSubject('Студент ' . $user . ': Ваше рассписание уроков в школе Св. Сергия Радонежского')
		->setFrom($this->getParameter('mailer_user'))
		->setTo(!empty($user->getRoutingEmail()) ? $user->getRoutingEmail() : $user->getEmail())
		->setBody(
				$body,
				'text/html'
		);

		$this->get('mailer')->send($message);
		return;
	}
	
}