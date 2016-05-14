<?php

// src/AppBundle/Entity/ExamType.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="ExamType")
 */
class ExamType
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
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    protected $code;
    
    /**
     * @ORM\OneToMany(targetEntity="ExamWeight", mappedBy="examType")
     * @ORM\JoinColumn(name="examTypeId", referencedColumnName="id")
     **/
    protected $examWeights;
    
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
     * @return ExamType
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
     * Set code
     *
     * @param string $code
     * @return ExamType
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->examWeights = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add examWeights
     *
     * @param \AppBundle\Entity\ExamWeight $examWeights
     * @return ExamType
     */
    public function addExamWeight(\AppBundle\Entity\ExamWeight $examWeights)
    {
        $this->examWeights[] = $examWeights;

        return $this;
    }

    /**
     * Remove examWeights
     *
     * @param \AppBundle\Entity\ExamWeight $examWeights
     */
    public function removeExamWeight(\AppBundle\Entity\ExamWeight $examWeights)
    {
        $this->examWeights->removeElement($examWeights);
    }

    /**
     * Get examWeights
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExamWeights()
    {
        return $this->examWeights;
    }
}
