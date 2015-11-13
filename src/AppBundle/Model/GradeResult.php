<?php

// src/AppBundle/Model/GradeResult.php
namespace AppBundle\Model;

class GradeResult {
	
	protected $exams;
	protected $students;
	protected $courses;
	protected $grades;
	protected $diligence;
	protected $discipline;
	protected $attendance;
	
	public function getExams() {
		return $this->exams;
	}
	public function setExams($exams) {
		$this->exams = $exams;
		return $this;
	}
	
	public function getStudents() {
		return $this->students;
	}
	public function setStudents($students) {
		$this->students = $students;
		return $this;
	}
	
	public function getCourses() {
		return $this->courses;
	}
	public function setCourses($courses) {
		$this->courses = $courses;
		return $this;
	}
	
	public function getGrades() {
		return $this->grades;
	}
	public function setGrades($grades) {
		$this->grades = $grades;
		return $this;
	}
	
	public function getDiligence() {
		return $this->diligence;
	}
	public function setDiligence($diligence) {
		$this->diligence = $diligence;
		return $this;
	}
	
	public function getDiscipline() {
		return $this->discipline;
	}
	public function setDiscipline($discipline) {
		$this->discipline = $discipline;
		return $this;
	}
	
	public function getAttendance() {
		return $this->attendance;
	}
	public function setAttendance($attendance) {
		$this->attendance = $attendance;
		return $this;
	}
	
	

}