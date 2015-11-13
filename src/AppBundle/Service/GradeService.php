<?php

// src/AppBundle/Service/GradeService.php
namespace AppBundle\Service;

use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;

use Doctrine\ORM\EntityManager;

use AppBundle\Model\GradeQuery;
use AppBundle\Model\GradeResult;

class GradeService {
	
	protected $em;
	
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}
	
	public function obtainGrades(GradeQuery $query = null) {
		$q = $this->em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addOrderBy ( 'c.ordinal' )
		->addOrderBy ( 'g.student' )
		->setParameter ( 'code', 'Course' );
		$this->applyQuery($query, $q);
		
		$examStudents = $q->getQuery ()->execute ();
		
		$q = $this->em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.course', 'co' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addGroupBy ( 'g.course' )
		->addOrderBy ( 'co.name' )
		->setParameter ( 'code', 'Course' );
		$this->applyQuery($query, $q);
		
		$examCourses = $q->getQuery ()->execute ();
		
		$q = $this->em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.course', 'co' )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->setParameter ( 'code', 'Course' );
		$this->applyQuery($query, $q);
		
		$gradeCourses = $q->getQuery ()->execute ();
		
		$cachedGradeCourses = [];
		
		foreach($gradeCourses as $examGrade) {
			$compoundId = array(
					$examGrade->getStudent()->getId(),
					$examGrade->getCourse()->getId(),
					$examGrade->getClass()->getId(),
					$examGrade->getExam()->getId()
			);
			$id = join('-', $compoundId);
			$cachedGradeCourses[$id] = $examGrade;
		}
		
		$q = $this->em->getRepository ( 'AppBundle:Exam' )
		->createQueryBuilder ( 'e' );
		
		$allExams = $q->getQuery ()->execute ();
		
		$q = $this->em->getRepository ( 'AppBundle:Exam' )
		->createQueryBuilder ( 'e' )
		->join ( 'e.examType', 'et' )
		->where ( 'et.code = :code' )
		->setParameter ( 'code', 'MidTerm' );
		
		$exams = $q->getQuery ()->execute ();
		
		$diligence = $this->obtainAverageGrades ( $query, 'Diligence' );
		$discipline = $this->obtainAverageGrades ( $query, 'Discipline' );
		
		$q = $this->em->getRepository ( 'AppBundle:GradeAttendance' )
		->createQueryBuilder ( 'g' )
		->join ( 'g.year', 'y' )
		->where ( 'y.active = true' );
		
		$attendanceGrades = $q->getQuery ()->execute ();
		
		$cachedAttendance = [];
		
		foreach($attendanceGrades as $attendance) {
			$compoundId = array(
					$attendance->getStudent()->getId(),
					$attendance->getExam()->getId()
			);
			$id = join('-', $compoundId);
			$cachedAttendance[$id] = $attendance;
		}
		
		$result = new GradeResult();
		$result->setStudents($examStudents);
		$result->setCourses($examCourses);
		$result->setGrades($cachedGradeCourses);
		$result->setExams($allExams);
		$result->setDiligence($diligence);
		$result->setDiscipline($discipline);
		$result->setAttendance($cachedAttendance);
		
		return $result;
	}
	
	private function obtainAverageGrades(GradeQuery $query = null, $gradeTypeCode) {
		$q = $this->em->getRepository ( 'AppBundle:GradeExam' )
		->createQueryBuilder ( 'g' )
		->select ( "gt.name as name, e.id as examId, s.id as studentId, c.id as classId, avg(g.grade) as grade" )
		->join ( 'g.class', 'c' )
		->join ( 'c.year', 'y' )
		->join ( 'g.student', 's' )
		->join ( 'g.exam', 'e' )
		->join ( 'g.gradeType', 'gt' )
		->where ( 's.active = true' )
		->andWhere ( 'y.active = true' )
		->andWhere ( 'gt.code = :code' )
		->addGroupBy ( 'g.class' )
		->addGroupBy ( 'g.student' )
		->addGroupBy ( 'g.exam' )
		->addOrderBy ( 'g.class' )
		->addOrderBy ( 'g.student' )
		->addOrderBy ( 'g.exam' )
		->setParameter ( 'code', $gradeTypeCode );
		$this->applyQuery($query, $q);
		
		$grades = $q->getQuery ()->execute ();
		
		$cachedGrades = [];
		
		foreach($grades as $examGrade) {
			$compoundId = array(
					$examGrade['studentId'],
					$examGrade['classId'],
					$examGrade['examId']
			);
			$id = join('-', $compoundId);
			$cachedGrades[$id] = $examGrade;
		}
		
		return $cachedGrades;
	}
	
	private function applyQuery(GradeQuery $query = null, $q) {
		if($query !== null) {
			$teacherId = $query->getTeacherId();
			if(isset($teacherId)) {
				$q->andWhere('g.teacher = :teacherId');
				$q->setParameter ( 'teacherId', $query->getTeacherId());
			}
			$studentId = $query->getStudentId();
			if(isset($studentId)) {
				$q->andWhere('g.student = :studentId');
				$q->setParameter ( 'studentId', $query->getStudentId());
			}
			$parentId = $query->getParentId();
			if(isset($parentId)) {
				$q->join ( 's.parents', 'p' );
				$q->andWhere('p.id = :parentId');
				$q->setParameter ( 'parentId', $query->getParentId());
			}
		}
	}
}