<?php

// src/AppBundle/Model/GradeQuery.php
namespace AppBundle\Model;

class GradeQuery {
	
	protected $teacherId;
	
	protected $parentId;
	
	protected $studentId;
	
	public function setTeacherId($teacherId) {
		$this->teacherId = $teacherId;
	}
	
	public function getTeacherId() {
		return $this->teacherId;
	}
	
	public function setParentId($parentId) {
		$this->parentId = $parentId;
	}
	
	public function getParentId() {
		return $this->parentId;
	}
	
	public function setStudentId($studentId) {
		$this->studentId = $studentId;
	}
	
	public function getStudentId() {
		return $this->studentId;
	}
}