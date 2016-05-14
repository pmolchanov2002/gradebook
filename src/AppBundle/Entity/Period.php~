<?php

// src/AppBundle/Entity/Period.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="Period")
 */
class Period
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
     * @ORM\Column()
     * @Assert\Range(
     *      min = 0,
     *      max = 10000,
     *      minMessage = "Minimum sort order is {{ limit }}",
     *      maxMessage = "Maximum sort order is {{ limit }}"
     * )
     */
    protected $ordinal;    

    /**
     * @ORM\Column(name="startTime", type="time")
     * @Assert\NotBlank()
     */    
    protected $startTime;

    /**
     * @ORM\Column(name="endTime", type="time")
     * @Assert\NotBlank()
     */    
    protected $endTime;

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
     * @return Period
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
     * Set startTime
     *
     * @param \DateTime $startTime
     * @return Period
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     * @return Period
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Period
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
     * @return Period
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
     * Set ordinal
     *
     * @param string $ordinal
     * @return Period
     */
    public function setOrdinal($ordinal)
    {
        $this->ordinal = $ordinal;

        return $this;
    }

    /**
     * Get ordinal
     *
     * @return string 
     */
    public function getOrdinal()
    {
        return $this->ordinal;
    }

    /**
     * Set englishName
     *
     * @param string $englishName
     * @return Period
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
