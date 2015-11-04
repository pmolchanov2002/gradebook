<?php

// src/AppBundle/Entity/GradeAttendance.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\Exam;
use AppBundle\Entity\User;
use AppBundle\Entity\Year;

/**
 * @ORM\Entity
 * @ORM\Table(name="GradeAttendance")
 */
class GradeAttendance
{

	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="Exam")
	 * @ORM\JoinColumn(name="ExamId", referencedColumnName="id")
	 **/
	protected $exam;
	
    /** 
     * @ORM\Id 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="StudentId", referencedColumnName="id")
     **/
    protected $student;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Year")
     * @ORM\JoinColumn(name="YearId", referencedColumnName="id")
     **/
    protected $year;    
    
    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     **/
    protected $grade;
    
    /**
     * @ORM\Column(name="MaxGrade")
     * @Assert\NotBlank()
     **/
    protected $maxGrade;    

    /**
     * Set grade
     *
     * @param string $grade
     * @return GradeAttendance
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set maxGrade
     *
     * @param string $maxGrade
     * @return GradeAttendance
     */
    public function setMaxGrade($maxGrade)
    {
        $this->maxGrade = $maxGrade;

        return $this;
    }

    /**
     * Get maxGrade
     *
     * @return string 
     */
    public function getMaxGrade()
    {
        return $this->maxGrade;
    }

    /**
     * Set exam
     *
     * @param \AppBundle\Entity\Exam $exam
     * @return GradeAttendance
     */
    public function setExam(\AppBundle\Entity\Exam $exam)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam
     *
     * @return \AppBundle\Entity\Exam 
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\User $student
     * @return GradeAttendance
     */
    public function setStudent(\AppBundle\Entity\User $student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\User 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set year
     *
     * @param \AppBundle\Entity\Year $year
     * @return GradeAttendance
     */
    public function setYear(\AppBundle\Entity\Year $year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return \AppBundle\Entity\Year 
     */
    public function getYear()
    {
        return $this->year;
    }
}
