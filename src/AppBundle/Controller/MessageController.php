<?php

// src/AppBundle/Controller/PendingGradesController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\Notification;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;

class MessageController extends Controller {
	
	private $displayRoute = 'app_mail_message_success';
	
	/**
	 * @Route("/admin/message/role")
	 */
	public function displayRoles() {
		$roles = $this->getDoctrine ()->getRepository ( "AppBundle:Role" )->findAll ();
		return $this->render ( 'forms/message/role.html.twig', array (
				'roles' => $roles
		) );
	}
	
	/**
	 * @Route("/admin/message/allroles")
	 */
	public function displayAllMessageForm(Request $request) {
	
		$notification = new Notification();
	
		$form = $this->createFormBuilder($notification)
		->add('subject', 'text', array('label' => 'Subject:'))
		->add('message', 'markdown', array('label' => 'Message:'))
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Recepients: ',
				'query_builder' => function (EntityRepository $er) use ($role) {
				return $er->createQueryBuilder('u')
				->join('u.roles', 'r')
				->where('u.active=true')
				->andWhere('u.email is not NULL')
				->orderBy('u.lastName', 'ASC');
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
	 * @Route("/admin/message/role/{role}")
	 * @ParamConverter("role", class="AppBundle:Role")
	 */
	public function displayMessageForm($role, Request $request) {
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('subject', 'text', array('label' => 'Subject:'))
		->add('message', 'markdown', array('label' => 'Message:'))
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Recepients: ',
				'query_builder' => function (EntityRepository $er) use ($role) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->where('u.active=true')
					->andWhere('r.id=:role')
					->andWhere('u.email is not NULL')
					->orderBy('u.lastName', 'ASC')
					->setParameter('role', $role->getId());
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
	 * @Route("/admin/mail/message/success", name="app_mail_message_success")
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
		
		$body = $this->renderView(
				'mail/message.html.twig',
				array(
						'notification' => $notification,
						'user' => $user
				)
		);
		
		$message = \Swift_Message::newInstance()
		->setSubject($notification->getSubject())
		->setFrom($this->getParameter('mailer_user'))
		->setTo($user->getEmail())
		->setBody(
				$body,
				'text/html'
		);
		$this->get('mailer')->send($message);
		return;
	}
	
}