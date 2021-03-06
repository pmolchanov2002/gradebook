<?php

// src/AppBundle/Controller/TeacherMessageController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\Notification;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class TeacherMessageController extends Controller {
	
	private $displayRoute = 'app_mail_schedule_success';
	
	/**
	 * @Route("/admin/message/teacher")
	 */
	public function displayMessageForm(Request $request) {
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Teachers: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->join('u.lessons', 'l')
					->join('l.classOfStudents', 'c')
					->join('c.year', 'y')
					->where('u.active=true')
					->andWhere('y.active=true')
					->andWhere('r.role=:role')
					->andWhere('u.email is not NULL')
					->orderBy('u.lastName', 'ASC')
					->setParameter('role', 'ROLE_TEACHER');
				}
		))
		->add('substitutes', 'entity', array(
			'multiple' => true,
			'expanded' => true,
			'class' => 'AppBundle:User',
			'label' => 'Substitutes: ',
			'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('u')
				->join('u.roles', 'r')
				->join('u.substituteLessons', 'l')
				->join('l.classOfStudents', 'c')
				->join('c.year', 'y')
				->where('u.active=true')
				->andWhere('y.active=true')
				->andWhere('r.role=:role')
				->andWhere('u.email is not NULL')
				->orderBy('u.lastName', 'ASC')
				->setParameter('role', 'ROLE_TEACHER');
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

			foreach ($notification->getSubstitutes() as $recipient) {
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
	 * @Route("/admin/mail/message/success", name="app_mail_schedule_success")
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

		// Get teacher's schedule from  the database
		$q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl left join cl.students s left join cl.year y left join l.period p left join l.teacher t where t.id=:id and y.active=true order by p.ordinal, cl.ordinal, s.lastName")
		->setParameter("id", $user->getId());
		$teacher_lessons = $q->getResult();

		$q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl left join cl.students s left join cl.year y left join l.period p left join l.substitute t where t.id=:id and y.active=true order by p.ordinal, cl.ordinal, s.lastName")
		->setParameter("id", $user->getId());
		$sub_lessons = $q->getResult();

		$lessons = new ArrayCollection(array_merge($teacher_lessons, $sub_lessons));

		$iterator = $lessons->getIterator();
		$iterator->uasort(function ($a, $b) {
			return ($a->getPeriod()->getStartTime() < $b->getPeriod()->getStartTime()) ? -1 : 1;
		});
		$lessons = new ArrayCollection(iterator_to_array($iterator));
		
		// Create Russian message

		$notification->setMessage($this->renderView('mail/scheduleTeacher.html.twig', array(
				"lessons" => $lessons,
				'user' => $user
		)));

		// Create English message
		
		$notification->setEnglishMessage($this->renderView('mail/scheduleTeacherEnglish.html.twig', array(
				"lessons" => $lessons,
				'user' => $user
		)));
		
		// Create message to send 
		
		$body = $this->renderView(
				'mail/teacherMessage.html.twig',
				array(
						'notification' => $notification,
						'user' => $user
		));

		$message = \Swift_Message::newInstance()
		->setSubject('Учитель ' . $user . ': Ваше рассписание уроков в школе Св. Сергия Радонежского')
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