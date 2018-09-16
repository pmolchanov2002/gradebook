<?php

// src/AppBundle/Entity/ExamType.php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="EventType")
 */
class EventType
{
	 /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(name="EnTitle", unique=true)
     * @Assert\NotBlank()
     */
    protected $enTitle;
    
    /**
     * @ORM\Column(name="RuTitle", unique=true)
     * @Assert\NotBlank()
     */
    protected $ruTitle;

     /**
     * @ORM\Column(name="EnDescription", unique=true)
     * @Assert\NotBlank()
     */
    protected $enDescription;
    
    /**
     * @ORM\Column(name="RuDescription", unique=true)
     * @Assert\NotBlank()
     */
    protected $ruDescription;

}
