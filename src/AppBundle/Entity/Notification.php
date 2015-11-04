<?php

// src/AppBundle/Entity/ClassOfStudents.php
namespace AppBundle\Entity;
use AppBundle\Entity\User;
use AppBundle\Entity\Exam;

class Notification
{
	protected $exam;
       
    protected $teachers;
    
    /**
     * Set name
     *
     * @param string $name
     * @return ClassOfStudents
     */
    public function setExam(\AppBundle\Entity\Exam $exam)
    {
    	$this->exam = $exam;
    
    	return $this;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getExam()
    {
    	return $this->exam;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teachers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add students
     *
     * @param \AppBundle\Entity\User $students
     * @return ClassOfStudents
     */
    public function addTeacher(\AppBundle\Entity\User $teachers)
    {
        $this->teachers[] = $teachers;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \AppBundle\Entity\User $students
     */
    public function removeTeacher(\AppBundle\Entity\User $teachers)
    {
        $this->teachers->removeElement($teachers);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeachers()
    {
        return $this->teachers;
    }
}
