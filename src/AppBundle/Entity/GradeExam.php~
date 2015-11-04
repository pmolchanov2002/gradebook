<?php

// src/AppBundle/Entity/GradeExam.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\Exam;
use AppBundle\Entity\User;
use AppBundle\Entity\Course;
use AppBundle\Entity\ClassOfStudents;
use AppBundle\Entity\GradeType;

/**
 * @ORM\Entity
 * @ORM\Table(name="GradeExam")
 */
class GradeExam
{
	

    /** 
     * @ORM\Id 
     * @ORM\ManyToOne(targetEntity="Exam")
     * @ORM\JoinColumn(name="examId", referencedColumnName="id")
     **/
    protected $exam;

    /** 
     * @ORM\Id 
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="studentId", referencedColumnName="id")
     **/
    protected $student;

    /** 
     * @ORM\Id 
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(name="courseId", referencedColumnName="id")
     **/
    protected $course;   

    /** 
     * @ORM\Id 
     * @ORM\ManyToOne(targetEntity="ClassOfStudents")
     * @ORM\JoinColumn(name="classId", referencedColumnName="id")
     **/
    protected $class;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="GradeType")
     * @ORM\JoinColumn(name="GradeTypeId", referencedColumnName="id")
     **/
    protected $gradeType;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="teacherId", referencedColumnName="id")
     **/
    protected $teacher;    

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 100,
     *      minMessage = "Minimum grade is {{ limit }}",
     *      maxMessage = "Maximum grade is {{ limit }}"
     * )
     */
    protected $grade;

    /**
     * @ORM\Column(type="datetime")
     */    
    protected $created;



    /**
     * Set grade
     *
     * @param integer $grade
     * @return GradeExam
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return integer 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return GradeExam
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set exam
     *
     * @param \AppBundle\Entity\Exam $exam
     * @return GradeExam
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
     * @return GradeExam
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
     * Set teacher
     *
     * @param \AppBundle\Entity\User $teacher
     * @return GradeExam
     */
    public function setTeacher(\AppBundle\Entity\User $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\User 
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     * @return GradeExam
     */
    public function setCourse(\AppBundle\Entity\Course $course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \AppBundle\Entity\Course 
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set class
     *
     * @param \AppBundle\Entity\ClassOfStudents $class
     * @return GradeExam
     */
    public function setClass(\AppBundle\Entity\ClassOfStudents $class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return \AppBundle\Entity\ClassOfStudents 
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set gradeType
     *
     * @param \AppBundle\Entity\GradeType $gradeType
     * @return GradeExam
     */
    public function setGradeType(\AppBundle\Entity\GradeType $gradeType = null)
    {
        $this->gradeType = $gradeType;

        return $this;
    }

    /**
     * Get gradeType
     *
     * @return \AppBundle\Entity\GradeType 
     */
    public function getGradeType()
    {
        return $this->gradeType;
    }
}
