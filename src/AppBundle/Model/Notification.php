<?php

// src/AppBundle/Model/Notification.php
namespace AppBundle\Model;
use AppBundle\Entity\User;
use AppBundle\Entity\Exam;

class Notification
{
	protected $exam;    
	protected $users;
	protected $substitutes;
    protected $subject;
    protected $message;
    protected $englishMessage;
    
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

	public function getSubstitutes() {
		return $this->substitutes;
	}
	public function setSubstitutes($substitutes) {
		$this->substitutes = $substitutes;
		return $this;
	}

	public function getMessage() {
		return $this->message;
	}
	public function setMessage($message) {
		$this->message = $message;
		return $this;
	}
	
	public function getEnglishMessage() {
		return $this->englishMessage;
	}
	public function setEnglishMessage($message) {
		$this->englishMessage = $message;
		return $this;
	}
	
	public function getSubject() {
		return $this->subject;
	}
	public function setSubject($subject) {
		$this->subject = $subject;
		return $this;
	}
}
