<?php

// src/AppBundle/Entity/ClassOfStudents.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Year;
use AppBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="Class")
 */
class ClassOfStudents
{
       
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @ORM\Column(name="EnglishName", unique=true)
     * @Assert\NotBlank()
     */
    protected $englishName;    
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $ordinal;

    /**
     * @ORM\ManyToOne(targetEntity="Year")
     * @ORM\JoinColumn(name="YearId", referencedColumnName="id")
     */
    protected $year;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="Class_Student",
     *      joinColumns={@ORM\JoinColumn(name="ClassId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="StudentId", referencedColumnName="id")}
     *      )
     **/
    protected $students;

    /** 
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="classOfStudents")
     * @ORM\JoinColumn(name="courseId", referencedColumnName="id")
     **/
    protected $lessons;

     /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    public function createClone() {
    	$result = new ClassOfStudents();
    	$result->name = $this->name;
    	$result->englishName = $this->englishName;
    	$result->ordinal = $this->ordinal;
    	$result->year = $this->year;
    	$result->students = $this->students;
    	return $result;
    }
 

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
     * Set name
     *
     * @param string $name
     * @return ClassOfStudents
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return ClassOfStudents
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ClassOfStudents
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
     * Set year
     *
     * @param \AppBundle\Entity\Year $year
     * @return ClassOfStudents
     */
    public function setYear(\AppBundle\Entity\Year $year = null)
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add students
     *
     * @param \AppBundle\Entity\User $students
     * @return ClassOfStudents
     */
    public function addStudent(\AppBundle\Entity\User $students)
    {
        $this->students[] = $students;

        return $this;
    }

    /**
     * Remove students
     *
     * @param \AppBundle\Entity\User $students
     */
    public function removeStudent(\AppBundle\Entity\User $students)
    {
        $this->students->removeElement($students);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add lessons
     *
     * @param \AppBundle\Entity\Lesson $lessons
     * @return ClassOfStudents
     */
    public function addLesson(\AppBundle\Entity\Lesson $lessons)
    {
        $this->lessons[] = $lessons;

        return $this;
    }

    /**
     * Remove lessons
     *
     * @param \AppBundle\Entity\Lesson $lessons
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lessons)
    {
        $this->lessons->removeElement($lessons);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * Set ordinal
     *
     * @param integer $ordinal
     * @return ClassOfStudents
     */
    public function setOrdinal($ordinal)
    {
        $this->ordinal = $ordinal;

        return $this;
    }

    /**
     * Get ordinal
     *
     * @return integer 
     */
    public function getOrdinal()
    {
        return $this->ordinal;
    }

    /**
     * Set englishName
     *
     * @param string $englishName
     * @return ClassOfStudents
     */
    public function setEnglishName($englishName)
    {
        $this->englishName = $englishName;

        return $this;
    }

    /**
     * Get englishName
     *
     * @return string 
     */
    public function getEnglishName()
    {
        return $this->englishName;
    }
}
