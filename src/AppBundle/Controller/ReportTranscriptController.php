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

class ReportTranscriptController extends Controller {
	
	private $displayRoute = 'app_transcript_success';
	
	/**
	 * @Route("/admin/report/transcript")
	 */
	public function displayMessageForm(Request $request) {
		
		$notification = new Notification();
		
		$form = $this->createFormBuilder($notification)
		->add('users', 'entity', array(
				'multiple' => false,
				'expanded' => true,
				'required' => true,
				'class' => 'AppBundle:User',
				'label' => 'Recepients: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->where('u.active=true')
					->andWhere('r.role=:role')
					->orderBy('u.lastName', 'ASC')
					->setParameter('role', 'ROLE_STUDENT');
				}
		))
		->add('send', 'submit', array('label' => 'Create'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$notification = $form->getData();
			return $this->display_report($notification);
		}
		
		return $this->render('forms/message/mail.html.twig', array(
				'form' => $form->createView()
		));
    
	}

	public function display_report($notification) {
		$student = $notification->getUsers();

		$em = $this->getDoctrine()->getManager();

		$summary = $em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.course', 'co' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->join ( 'g.exam', 'e' )
		->where ( 's.active = true' )
		->andWhere ( 's.id = :id' )
		->andWhere ( 'gt.id = 1' )
		->andWhere ( 'e.id = 3' )
		->groupBy('co.id')
		->setParameter ( 'id', $student->getId() )
		->select('co.englishName as name, SUM(y.lessonCount * 0.6) as hours')
		->getQuery()
		->execute();

		$details = $em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.course', 'co' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->join ( 'g.exam', 'e' )
		->where ( 's.active = true' )
		->andWhere ( 's.id = :id' )
		->andWhere ( 'gt.id = 1' )
		->andWhere ( 'e.id = 3' )
		->setParameter ( 'id', $student->getId() )
		->select('co.englishName as courseName, y.name as yearName, c.englishName as className, y.lessonCount * 0.6 as hours, g.grade as grade')
		->getQuery()
		->execute();

		return $this->render('report/transcriptPrint.html.twig', array(
			'summary' => $summary,
			'details' => $details,
			'student' => $student
		));
	}

	
}