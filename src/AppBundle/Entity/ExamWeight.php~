<?php

// src/AppBundle/Entity/ExamWeight.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use AppBundle\Entity\ExamType;
use AppBundle\Entity\GradeType;

/**
 * @ORM\Entity
 * @ORM\Table(name="ExamWeight")
 */
class ExamWeight
{    

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="ExamType")
     * @ORM\JoinColumn(name="ExamTypeId", referencedColumnName="id")
     **/
    protected $examType;
    
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="GradeType")
     * @ORM\JoinColumn(name="GradeTypeId", referencedColumnName="id")
     **/
    protected $gradeType;
    
    /**
     * @ORM\Column(name="Weight")
     */
    protected $weight;

    /**
     * Set weight
     *
     * @param string $weight
     * @return ExamWeight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string 
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set examType
     *
     * @param \AppBundle\Entity\ExamType $examType
     * @return ExamWeight
     */
    public function setExamType(\AppBundle\Entity\ExamType $examType)
    {
        $this->examType = $examType;

        return $this;
    }

    /**
     * Get examType
     *
     * @return \AppBundle\Entity\ExamType 
     */
    public function getExamType()
    {
        return $this->examType;
    }

    /**
     * Set gradeType
     *
     * @param \AppBundle\Entity\GradeType $gradeType
     * @return ExamWeight
     */
    public function setGradeType(\AppBundle\Entity\GradeType $gradeType)
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
