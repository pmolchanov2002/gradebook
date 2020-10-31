<?php

// src/AppBundle/Controller/HomeworkController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormError;
use AppBundle\Model\Notification;

class HomeworkController extends Controller {
	private $displayRoute = 'app_homework_display';
	
	/**
	 * @Route("/teacher/homework")
	 */
	public function displayClasses() {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select cl from AppBundle:ClassOfStudents cl join cl.lessons l left join l.teacher t join l.course c join cl.year y where t.id=:id and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () );
		
		$classes = $q->getResult ();
		
		return $this->render ( 'views/teacher/homework/class.html.twig', array (
				'classes' => $classes 
		) );
	}
	
	/**
	 * @Route("/teacher/homework/class/{classId}")
	 */
	public function displayCourses($classId) {
		$class = $this->getClass ( $classId );
		if (! isset ( $class )) {
			return new Response ( "Class is not permitted" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select c from AppBundle:Course c join c.lessons l join l.classOfStudents cl join cl.year y left join l.teacher t where t.id=:id and cl.id = :classId and  y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "classId", $classId );
		$courses = $q->getResult ();
		
		return $this->render ( 'views/teacher/homework/course.html.twig', array (
				'courses' => $courses,
				'class' => $class 
		) );
	}
	
	/**
	 * @Route("/teacher/homework/class/{classId}/course/{courseId}")
	 */
	public function displayEmailForm($courseId, $classId, Request $request) {
		return 'Not available';
		$course = $this->getCourse ( $courseId );
		if (! isset ( $course )) {
			return new Response ( "Course is not permitted" );
		}
		
		$class = $this->getClass (  $classId );
		if (! isset ( $class )) {
			return new Response ( "Class is not permitted" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select u from AppBundle:User u join u.classes cl join cl.year y join cl.lessons l left join l.teacher t join l.course c where t.id=:id and c.id=:courseId and cl.id=:classId and u.active=true and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId )->setParameter ( "classId", $classId );
		
		$students = $q->getResult ();
		
		$teacherId = $this->get ( 'security.context' )->getToken ()->getUser ()->getId();
		
		$teacher = $this->getDoctrine()->getRepository("AppBundle:User")->findOneById($teacherId);
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('subject', 'text', array(
				'label' => 'Subject:',
				'data' => 'Homework for '.$course->getEnglishName().' ('.$class->getEnglishName().') / Домашнее задание по предмету '.$course->getName().' ('.$class->getName().')',
		))
		->add('message', 'markdown', array('label' => 'Message:'))
		->add('select', 'button', array('label' => 'Select All'))
		->add('users', 'entity', array(
				'multiple' => true,
				'expanded' => true,
				'class' => 'AppBundle:User',
				'label' => 'Parents: ',
				'query_builder' => function (EntityRepository $er) use ($teacherId, $courseId, $classId) {
					return $er->createQueryBuilder('p')
					->join('p.students', 'u')
					->join('u.classes', 'cl')
					->join('cl.year', 'y')
					->join('cl.lessons', 'l')
					->join('l.teacher', 't')
					->join('l.course', 'c')
					->where('t.id=:id')
					->andWhere('c.id=:courseId')
					->andWhere('cl.id=:classId')
					->andWhere('u.active=true')
					->andWhere('y.active=true')
					->andWhere('p.email is not NULL')
					->orderBy('u.lastName', 'ASC')
					->setParameter ( "id", $teacherId)
					->setParameter ( "courseId", $courseId )
					->setParameter ( "classId", $classId );
				}
		))
		->add('send', 'submit', array('label' => 'Send'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$notification = $form->getData();
			$recipients = array();
			foreach ($notification->getUsers() as $recipient) {
				$recipients[] = $recipient->__toString();
			}
			$this->sendEmail($notification, $teacher);
			
			$session = $request->getSession();
			$session->set('recipients', $recipients);
			
			return $this->redirectToRoute($this->displayRoute);
		}
		
		return $this->render('views/teacher/homework/homework.html.twig', array(
				'form' => $form->createView()
		));
    
	}
	
	/**
	 * @Route("/teacher/mail/message/success", name="app_homework_display")
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
	
	private function sendEmail($notification, $teacher) {
		
		$body = $this->renderView(
				'mail/homework.html.twig',
				array(
						'notification' => $notification,
						'teacher' => $teacher
				)
		);
		
		$recepients = array();
		
		foreach ($notification->getUsers() as $recipient) {
			$recipients[!empty($recipient->getRoutingEmail()) ? $recipient->getRoutingEmail() : $recipient->getEmail()] = $recipient->getFirstName().' '.$recipient->getLastName();
		}
		
		$message = \Swift_Message::newInstance()
		->setSubject($notification->getSubject())
		->setFrom($teacher->getEmail(), $teacher->getEnglishName())
		->setTo($recipients)
		->setCc(array($teacher->getEmail() => $teacher->getLastName().' '.$teacher->getFirstName()))
		->setBody(
				$body,
				'text/html'
		);
		$this->get('mailer')->send($message);
		return;
	}	
	
	private function getCourse($courseId) {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select c from AppBundle:Course c join c.lessons l left join l.teacher t where t.id=:id and c.id=:courseId" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId );
		$course = $q->getSingleResult ();
		return $course;
	}
	private function getClass($classId) {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select cl from AppBundle:ClassOfStudents cl join cl.year y join cl.lessons l left join l.teacher t join l.course c where t.id=:id and cl.id=:classId and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "classId", $classId );
		
		$class = $q->getSingleResult ();
		return $class;
	}
}