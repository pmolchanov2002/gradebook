<?php

// src/AppBundle/Controller/ScheduleController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Exam;
use AppBundle\Entity\GradeExam;

class ScheduleController extends Controller
{


    /**
     * @Route("/teacher/schedule")
     */       
    public function display() {

        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl join cl.year y left join l.period p left join l.teacher t where t.id=:id and y.active=true order by p.ordinal")
        ->setParameter("id", $this->get('security.context')->getToken()->getUser()->getId());   

        $lessons = $q->getResult();

        //print_r ($students);

        return $this->render('views/teacher/schedule.html.twig', array(
            "lessons" => $lessons
        ));
    }
}