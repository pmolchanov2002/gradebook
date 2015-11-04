<?php

// src/AppBundle/Controller/ReportTeacherController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;

class ReportTeacherController extends Controller
{
    /**
     * @Route("/teacher/report/grades")
     */       
    public function displayExams() {
    	$teacherId = $this->get('security.context')->getToken()->getUser()->getId();
    	
    	$em = $this->getDoctrine()->getManager();
   
    	$q = $em->getRepository('AppBundle:GradeExam')
    	->createQueryBuilder('g')
    	->join('g.class', 'c')
    	->join('c.year', 'y')
    	->join('g.student', 's')
    	->join('g.gradeType', 'gt')
    	->where('s.active = true')
    	->andWhere('y.active = true')
    	->andWhere('gt.code = :code')
    	->andWhere('g.teacher = :teacherId')
    	->addGroupBy('g.class')
    	->addGroupBy('g.student')
    	->addOrderBy('g.class')
    	->addOrderBy('g.student')
    	->setParameter('teacherId', $teacherId)
    	->setParameter('code', 'Course');
    	 
    	$examStudents = $q->getQuery()->execute();
    	
    	$q = $em->getRepository('AppBundle:GradeExam')
    	->createQueryBuilder('g')
    	->join('g.class', 'c')
    	->join('c.year', 'y')
    	->join('g.student', 's')
    	->join('g.gradeType', 'gt')
    	->where('s.active = true')
    	->andWhere('y.active = true')
    	->andWhere('gt.code = :code')
    	->andWhere('g.teacher = :teacherId')
    	->addGroupBy('g.class')
    	->addGroupBy('g.student')
    	->addGroupBy('g.course')
    	->addOrderBy('g.course')
    	->setParameter('teacherId', $teacherId)
    	->setParameter('code', 'Course');
    	
    	$examCourses = $q->getQuery()->execute();    	
    	
    	$q = $em->getRepository('AppBundle:GradeExam')
    	->createQueryBuilder('g')
    	->join('g.class', 'c')
    	->join('c.year', 'y')
    	->join('g.student', 's')
    	->join('g.gradeType', 'gt')
    	->where('s.active = true')
    	->andWhere('y.active = true')
    	->andWhere('gt.code = :code')
    	->andWhere('g.teacher = :teacherId')
    	->addOrderBy('g.class')
    	->addOrderBy('g.student')
    	->addOrderBy('g.course')
    	->addOrderBy('g.exam')
    	->setParameter('teacherId', $teacherId)
    	->setParameter('code', 'Course');
    	
    	$examGrades = $q->getQuery()->execute();
    	
    	$q = $em->getRepository('AppBundle:Exam')
    	->createQueryBuilder('e')
    	->join('e.examType', 'et');
    	 
    	$allExams = $q->getQuery()->execute();
    	
    	$q = $em->getRepository('AppBundle:Exam')
    	->createQueryBuilder('e')
    	->join('e.examType', 'et')
    	->where('et.code = :code')
    	->setParameter('code', 'MidTerm');
    	
    	$exams = $q->getQuery()->execute();    	
    	
    	$diligence = $this->avgGrade('Diligence');
    	$discipline = $this->avgGrade('Discipline');
    	
        return $this->render('views/teacher/grades.html.twig',  
        		array(
        				'examStudents' => $examStudents,
        				'examCourses' => $examCourses,
        				'examGrades' => $examGrades, 
        				'exams' => $exams,
        				'allExams' => $allExams,
        				'diligence' => $diligence,
        				'discipline' => $discipline
        		)
        );
    }
    
    private function avgGrade($gradeTypeCode) {
    	
    	$teacherId = $this->get('security.context')->getToken()->getUser()->getId();
    	
    	$em = $this->getDoctrine()->getManager();
    	$q = $em->getRepository('AppBundle:GradeExam')
    	->createQueryBuilder('g')
    	->select("gt.name as name, e.id as examId, s.id as studentId, c.id as classId, avg(g.grade) as grade")
    	->join('g.class', 'c')
    	->join('c.year', 'y')
    	->join('g.student', 's')
    	->join('g.exam', 'e')
    	->join('g.gradeType', 'gt')
    	->where('s.active = true')
    	->andWhere('y.active = true')
    	->andWhere('gt.code = :code')
    	->andWhere('g.teacher = :teacherId')
    	->addGroupBy('g.class')
    	->addGroupBy('g.student')
    	->addGroupBy('g.exam')
    	->addOrderBy('g.class')
    	->addOrderBy('g.student')
    	->addOrderBy('g.exam')
    	->setParameter('teacherId', $teacherId)
    	->setParameter('code', $gradeTypeCode);
    	 
    	return $q->getQuery()->execute();
    
    }
     
}