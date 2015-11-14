<?php

// src/AppBundle/Model/Notification.php
namespace AppBundle\Model;
use AppBundle\Entity\User;
use AppBundle\Entity\Exam;

class Notification
{
	protected $exam;    
    protected $users;
	public function getExam() {
		return $this->exam;
	}
	public function setExam($exam) {
		$this->exam = $exam;
		return $this;
	}
	public function getUsers() {
		return $this->users;
	}
	public function setUsers($users) {
		$this->users = $users;
		return $this;
	}
}
