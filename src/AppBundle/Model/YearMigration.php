<?php

// src/AppBundle/Model/Notification.php
namespace AppBundle\Model;

class YearMigration
{
	protected $previousYear;    
    protected $nextYear;
    
	public function getPreviousYear() {
		return $this->previousYear;
	}
	public function setPreviousYear($previousYear) {
		$this->previousYear = $previousYear;
		return $this;
	}
	public function getNextYear() {
		return $this->nextYear;
	}
	public function setNextYear($nextYear) {
		$this->nextYear = $nextYear;
		return $this;
	}
}
