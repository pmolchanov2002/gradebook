<?php

// src/AppBundle/Entity/Lesson.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use AppBundle\Entity\ClassOfStudents;
use AppBundle\Entity\Period;

/**
 * @ORM\Entity
 * @ORM\Table(name="Lesson")
 */
class Lesson
{
	 
     /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** 
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="lessons")
     * @ORM\JoinColumn(name="courseId", referencedColumnName="id")
     **/
    protected $course;

    /** 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lessons")
     * @ORM\JoinColumn(name="teacherId", referencedColumnName="id")     
     **/
    protected $teacher;

    /** 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lessons")
     * @ORM\JoinColumn(name="substituteId", referencedColumnName="id")     
     **/
    protected $substitute;

    /** 
     * @ORM\ManyToOne(targetEntity="ClassOfStudents", inversedBy="lessons")
     * @ORM\JoinColumn(name="classId", referencedColumnName="id")
     **/
    protected $classOfStudents;

    /** 
     * @ORM\ManyToOne(targetEntity="Period")
     * @ORM\JoinColumn(name="periodId", referencedColumnName="id")
     **/
    protected $period;  

    /**
     * @ORM\Column(name="MeetingLink", type="string", length=2048)
     */
    protected $meetingLink;

    /**
     * @ORM\Column(name="MeetingPassword", type="string", length=256)
     */
    protected $meetingPassword;

    /**
     * @ORM\Column(name="Comment", type="string", length=2048)
     */
    protected $comment;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     * @return Lesson
     */
    public function setCourse(\AppBundle\Entity\Course $course = null)
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
     * Set teacher
     *
     * @param \AppBundle\Entity\User $teacher
     * @return Lesson
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
     * Set substitute
     *
     * @param \AppBundle\Entity\User $substitute
     * @return Lesson
     */
    public function setSubstitute(\AppBundle\Entity\User $substitute = null)
    {
        $this->substitute = $substitute;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\User 
     */
    public function getSubstitute()
    {
        return $this->substitute;
    }

    /**
     * Set classOfStudents
     *
     * @param \AppBundle\Entity\ClassOfStudents $classOfStudents
     * @return Lesson
     */
    public function setClassOfStudents(\AppBundle\Entity\ClassOfStudents $classOfStudents = null)
    {
        $this->classOfStudents = $classOfStudents;

        return $this;
    }

    /**
     * Get classOfStudents
     *
     * @return \AppBundle\Entity\ClassOfStudents 
     */
    public function getClassOfStudents()
    {
        return $this->classOfStudents;
    }

    /**
     * Set period
     *
     * @param \AppBundle\Entity\Period $period
     * @return Lesson
     */
    public function setPeriod(\AppBundle\Entity\Period $period = null)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return \AppBundle\Entity\Period 
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set meetingLink
     *
     * @param string $meetingLink
     * @return User
     */
    public function setMeetingLink($meetingLink)
    {
        $this->meetingLink = $meetingLink;

        return $this;
    }

    /**
     * Get meetingLink
     *
     * @return string 
     */
    public function getMeetingLink()
    {
        return $this->meetingLink;
    }

    /**
     * Set meetingPassword
     *
     * @param string $meetingPassword
     * @return User
     */
    public function setMeetingPassword($meetingPassword)
    {
        $this->meetingPassword = $meetingPassword;

        return $this;
    }

    /**
     * Get meetingPassword
     *
     * @return string 
     */
    public function getMeetingPassword()
    {
        return $this->meetingPassword;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return User
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

}
