<?php

// src/AppBundle/Entity/FinalGrade.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="FinalGrades")
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
}
