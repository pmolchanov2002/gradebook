<?php

// src/AppBundle/Entity/Course.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Course")
 */
class Course
{
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(unique=true)
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @ORM\Column(name="EnglishName", unique=true)
     * @Assert\NotBlank()
     */
    protected $englishName;

    /** 
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="course")
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
     * @return Course
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
     * @return Course
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
     * @return Course
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
     * Constructor
     */
    public function __construct()
    {
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lessons
     *
     * @param \AppBundle\Entity\Lesson $lessons
     * @return Course
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
     * Set englishName
     *
     * @param string $englishName
     * @return Course
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
