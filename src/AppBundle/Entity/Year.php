<?php

// src/AppBundle/Entity/Year.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Year")
 */
class Year
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
     * @Assert\Length(
     *      min = 4,
     *      max = 50,
     *      minMessage = "Name of the study year must be at least {{ limit }} characters long",
     *      maxMessage = "Name of the study year cannot be longer than {{ limit }} characters"
     * )
     */
    protected $name;

     /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

     /**
     * @ORM\Column(type="datetime")
     */    
    protected $created;

    /**
     * @ORM\Column(type="boolean")
     */    
    protected $active;

	 /**
     * @ORM\Column(name="LessonCount",type="integer")
     */
    protected $lessonCount;
    
    public function __toString() {
    	return $this->name;
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
     * @return Year
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
     * @return Year
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
     * @return Year
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
     * Set active
     *
     * @param boolean $active
     * @return Year
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set lesson count. Number of lessons per year
     *
     * @param int $lessonCount
     * @return Year
     */
    public function setLessonCount($lessonCount)
    {
        $this->lessonCount = $lessonCount;

        return $this;
    }

    /**
     * Get lesson count. Number of lessons per year.
     *
     * @return int 
     */
    public function getLessonCount()
    {
        return $this->lessonCount;
    }
}
