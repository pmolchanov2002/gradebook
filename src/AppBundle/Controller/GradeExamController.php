<?php

// src/AppBundle/Controller/GradeExamController.php
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

class GradeExamController extends Controller {
	private $displayRoute = 'app_exam_display';
	
	/**
	 * @Route("/admin/gradeExam")
	 * 
	 */
	public function editGrade(Request $request) {
		
		$gradeExam = new GradeExam();
		
		$form = $this->createFormBuilder($gradeExam)
		->add('exam', 'entity', array(
				'multiple' => false,
				'class' => 'AppBundle:Exam',
				'choice_label' => 'name',
				'label' => 'Exam/period: '
		))
		->add('course', 'entity', array(
				'multiple' => false,
				'class' => 'AppBundle:Course',
				'choice_label' => 'name',
				'label' => 'Course: ',
				'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('c')
				->orderBy('c.name', 'ASC');
				}
		))
		->add('class', 'entity', array(
				'multiple' => false,
				'class' => 'AppBundle:ClassOfStudents',
				'choice_label' => 'name',
				'label' => 'Class: ',
				'query_builder' => function (EntityRepository $er) {
				return $er->createQueryBuilder('cl')
				->join('cl.year', 'y')
				->where('y.active = true')
				->orderBy('cl.ordinal', 'ASC');
				}
		))
		->add('student', 'entity', array(
				'multiple' => false,
				'class' => 'AppBundle:User',
				'label' => 'Student: ',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('u')
					->join('u.roles', 'r')
					->where('r.id = :id')
					->orderBy('u.lastName', 'ASC')
					->setParameter('id', 3);
					}
		))
		->add('gradeType', 'entity', array(
				'multiple' => false,
				'class' => 'AppBundle:GradeType',
				'choice_label' => 'name',
				'label' => 'Grade Type:'
		))
		->add('grade', 'text', array('label' => 'Grade:'))
		->add('save', 'submit', array('label' => 'Save'))
		->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$gradeExam = $form->getData();
			$savedGradeExam = $this->getDoctrine ()->getRepository ( "AppBundle:GradeExam" )->findOneBy(array(
				'student' => $gradeExam->getStudent(),
				'exam' => $gradeExam->getExam(),
				'course' => $gradeExam->getCourse(),
				'class' => $gradeExam->getClass(),
				'gradeType' => $gradeExam->getGradeType()
			));
			
			if(isset($savedGradeExam)) {
			
				$savedGradeExam->setGrade($gradeExam->getGrade());
				$em = $this->getDoctrine()->getManager();
				$em->persist($savedGradeExam);
				$em->flush();
				return $this->redirectToRoute("app_student_grade", array(
						"id" => $savedGradeExam->getStudent()->getId()
				));
			} else {
				$error = new FormError("Grade for this student/exam/course was not reported previously. You can modify only reported grades.");
				$form->addError($error);
			}
		}
		
		return $this->render('forms/gradeExam.html.twig', array(
				'form' => $form->createView(),
		));		
	}
	
	/**
	 * @Route("/teacher/exam")
	 */
	public function displayExams() {
		$exams = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findAll ();
		return $this->render ( 'views/teacher/exam.html.twig', array (
				'exams' => $exams 
		) );
	}
	
	/**
	 * @Route("/teacher/exam/{examId}/course")
	 */
	public function displayCourses($examId) {
		$exam = $this->getExam ( $examId );
		if (! isset ( $exam )) {
			return new Response ( "Exam not found" );
		}
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select c from AppBundle:Course c join c.lessons l join l.classOfStudents cl join cl.year y left join l.teacher t where t.id=:id and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () );
		$courses = $q->getResult ();
		
		return $this->render ( 'views/teacher/course.html.twig', array (
				'courses' => $courses,
				'examId' => $examId,
				'examName' => $exam->getName () 
		) );
	}
	
	/**
	 * @Route("/teacher/exam/{examId}/course/{courseId}")
	 */
	public function displayClasses($examId, $courseId) {
		$exam = $this->getExam ( $examId );
		if (! isset ( $exam )) {
			return new Response ( "Exam not found" );
		}
		
		$course = $this->getCourse ( $courseId );
		if (! isset ( $course )) {
			return new Response ( "Course is not permitted" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select cl from AppBundle:ClassOfStudents cl join cl.lessons l left join l.teacher t join l.course c join cl.year y where t.id=:id and c.id=:courseId and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId );
		
		$classes = $q->getResult ();
		
		return $this->render ( 'views/teacher/class.html.twig', array (
				'classes' => $classes,
				'examId' => $examId,
				'examName' => $exam->getName (),
				'courseId' => $courseId,
				'courseName' => $course->getName () 
		) );
	}
	
	/**
	 * @Route("/teacher/exam/{examId}/course/{courseId}/class/{classId}")
	 */
	public function displayStudents($examId, $courseId, $classId) {
		$exam = $this->getExam ( $examId );
		if (! isset ( $exam )) {
			return new Response ( "Exam not found" );
		}
		
		$course = $this->getCourse ( $courseId );
		if (! isset ( $course )) {
			return new Response ( "Course is not permitted" );
		}
		
		$class = $this->getClass ( $courseId, $classId );
		if (! isset ( $course )) {
			return new Response ( "Class is not permitted" );
		}
		
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select u from AppBundle:User u join u.classes cl join cl.year y join cl.lessons l left join l.teacher t join l.course c where t.id=:id and c.id=:courseId and cl.id=:classId and u.active=true and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId )->setParameter ( "classId", $classId );
		
		$students = $q->getResult ();
		
		$q = $em->createQuery ( "select gt from AppBundle:GradeType gt join gt.examWeights ew join ew.examType et where et.id = :examTypeId")->setParameter("examTypeId", $exam->getExamType()->getId());
		$gradeTypes = $q->getResult ();
		
		$grades = [ ];
		$allCorrect = true;
		
		$post = Request::createFromGlobals ();
		
		if ($post->request->has ( 'submit' )) {
			foreach ( $students as $student ) {
				if ($allCorrect) {
					foreach ( $gradeTypes as $gradeType ) {
						$grade = $post->request->get ( 'grade_' . $gradeType->getId () . '_' . $student->getId () );
						
						if (isset ( $grade )) {
							$gradeExam = new GradeExam ();
							$gradeExam->setExam ( $exam );
							$gradeExam->setStudent ( $student );
							$gradeExam->setCourse ( $course );
							$gradeExam->setClass ( $class );
							$gradeExam->setGradeType ( $gradeType );
							$gradeExam->setTeacher ( $this->get ( 'security.context' )->getToken ()->getUser () );
							if ($grade <= 100 && $grade >= 1) {
								$gradeExam->setGrade ( $grade );
							} else {
								$allCorrect = false;
								break;
							}
							$grades [] = $gradeExam;
						}
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
		
		$q = $em->getRepository ( 'AppBundle:GradeExam' )->createQueryBuilder ( 'g' )->where ( 'g.exam = :examId' )->andWhere ( 'g.class = :classId' )->andWhere ( 'g.course = :courseId' )->setParameter ( "examId", $examId )->setParameter ( "classId", $classId )->setParameter ( "courseId", $courseId );
		
		$grades = $q->getQuery ()->execute();
		
		$gradeExams = array ();
		
		$enableSubmit = false;
		
		foreach ( $students as $student ) {
			foreach ( $gradeTypes as $gradeType ) {
				$gradeExam = new GradeExam ();
				$gradeExam->setExam ( $exam );
				$gradeExam->setStudent ( $student );
				$gradeExam->setCourse ( $course );
				$gradeExam->setClass ( $class );
				$gradeExam->setGradeType ( $gradeType );
				
				$foundGrade = false;
				foreach ( $grades as $grade ) {
					if ($grade->getStudent()->getId() == $student->getId () && $grade->getGradeType ()->getId () == $gradeType->getId ()) {
						$gradeExam->setGrade ( $grade->getGrade () );
						$foundGrade = true;
					}
				}
				if(!$foundGrade && !$enableSubmit) {
					$enableSubmit = true;
				}
				$gradeExams [] = $gradeExam;
			}
		}
		
		return $this->render ( 'views/teacher/studentGrades.html.twig', array (
				'gradeExams' => $gradeExams,
				'classId' => $classId,
				'className' => $class->getName (),
				'examId' => $examId,
				'examName' => $exam->getName (),
				'courseId' => $courseId,
				'enableSubmit' => $enableSubmit,
				'courseName' => $course->getName (),
				'classId' => $classId,
				'className' => $class->getName () 
		) );
	}
	private function getExam($examId) {
		$exam = $this->getDoctrine ()->getRepository ( "AppBundle:Exam" )->findOneById ( $examId );
		return $exam;
	}
	private function getCourse($courseId) {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select c from AppBundle:Course c join c.lessons l left join l.teacher t where t.id=:id and c.id=:courseId" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId );
		$course = $q->getSingleResult ();
		return $course;
	}
	private function getClass($courseId, $classId) {
		$em = $this->getDoctrine ()->getManager ();
		$q = $em->createQuery ( "select cl from AppBundle:ClassOfStudents cl join cl.year y join cl.lessons l left join l.teacher t join l.course c where t.id=:id and c.id=:courseId and cl.id=:classId and y.active=true" )->setParameter ( "id", $this->get ( 'security.context' )->getToken ()->getUser ()->getId () )->setParameter ( "courseId", $courseId )->setParameter ( "classId", $classId );
		
		$class = $q->getSingleResult ();
		return $class;
	}
}