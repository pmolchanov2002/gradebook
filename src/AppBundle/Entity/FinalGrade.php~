<?php

// src/AppBundle/Entity/FinalGrade.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="FinalGradesAndAttendance")
 */
class FinalGrade
{    

    /**
     * @ORM\Id
     * @ORM\Column(name="name")
     */
    protected $name;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="firstName")
     */
    protected $firstName;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="lastName")
     */
    protected $lastName;
    
    /**
     * @ORM\Column(name="grade")
     */
    protected $grade;
    
    /**
     * @ORM\Column(name="attendanceGrade")
     */
    protected $attendanceGrade;
    
    /**
     * @ORM\Column(name="attendanceMaxGrade")
     */
    protected $attendanceMaxGrade;
    
    /**
     * @ORM\Column(name="attendancePercentage")
     */
    protected $attendancePercentage;


    /**
     * Set name
     *
     * @param string $name
     * @return FinalGrade
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return FinalGrade
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return FinalGrade
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set grade
     *
     * @param \double $grade
     * @return FinalGrade
     */
    public function setGrade(\double $grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return \double 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set attendanceGrade
     *
     * @param string $attendanceGrade
     * @return FinalGrade
     */
    public function setAttendanceGrade($attendanceGrade)
    {
        $this->attendanceGrade = $attendanceGrade;

        return $this;
    }

    /**
     * Get attendanceGrade
     *
     * @return string 
     */
    public function getAttendanceGrade()
    {
        return $this->attendanceGrade;
    }

    /**
     * Set attendanceMaxGrade
     *
     * @param string $attendanceMaxGrade
     * @return FinalGrade
     */
    public function setAttendanceMaxGrade($attendanceMaxGrade)
    {
        $this->attendanceMaxGrade = $attendanceMaxGrade;

        return $this;
    }

    /**
     * Get attendanceMaxGrade
     *
     * @return string 
     */
    public function getAttendanceMaxGrade()
    {
        return $this->attendanceMaxGrade;
    }

    /**
     * Set attendancePercentage
     *
     * @param string $attendancePercentage
     * @return FinalGrade
     */
    public function setAttendancePercentage($attendancePercentage)
    {
        $this->attendancePercentage = $attendancePercentage;

        return $this;
    }

    /**
     * Get attendancePercentage
     *
     * @return string 
     */
    public function getAttendancePercentage()
    {
        return $this->attendancePercentage;
    }
}
