<?php

// src/AppBundle/Controller/LessonController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\Lesson;


class LessonController extends Controller
{

    private $displayRoute = 'app_lesson_display';

    /**
     * @Route("/admin/lesson/create")
     */
    public function create(Request $request)
    {
        $lesson = new Lesson();
        $form = $this->createFormBuilder($lesson)
            ->add('course', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:Course',
                'choice_label' => 'name',
                'label' => 'Course: ',
            	'query_builder' => function (EntityRepository $er) {
            		return $er->createQueryBuilder('p')
            		->orderBy('p.name', 'ASC');
            		}
            ))
            ->add('period', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:Period',
                'choice_label' => 'name',
                'label' => 'Period: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.ordinal', 'ASC');
                }
            ))
            ->add('classOfStudents', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:ClassOfStudents',
                'choice_label' => 'name',
                'label' => 'Class: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.year', 'y')
                        ->where('y.active=:active')
                        ->orderBy('p.ordinal', 'ASC')
                        ->setParameter('active', true);
                }
            ))
            ->add('teacher', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:User',
                'label' => 'Teacher: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.roles', 'r')
                        ->where('r.id = :id')
                        ->orderBy('u.lastName', 'ASC')
                        ->setParameter('id', 2);
                },
            ))                         
            ->add('meetingLink', 'text', array('label' => 'Meeting Link:', 'required' => false))
        	->add('meetingPassword', 'text', array('label' => 'Meeting Password:', 'required' => false))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $lesson = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/lesson.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/lesson/edit/{id}")
     * @ParamConverter("lesson", class="AppBundle:Lesson")     
     */
    public function edit($lesson, Request $request)
    {
        if(!isset($lesson)) {
            return new Response("lesson not found");
        }
        $form = $this->createFormBuilder($lesson)
            ->add('course', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:Course',
                'choice_label' => 'name',
                'label' => 'Course: ',
            	'query_builder' => function (EntityRepository $er) {
            		return $er->createQueryBuilder('p')
            		->orderBy('p.name', 'ASC');
            		}
            ))
            ->add('period', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:Period',
                'choice_label' => 'name',
                'label' => 'Period: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.ordinal', 'ASC');
                }
            ))
            ->add('classOfStudents', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:ClassOfStudents',
                'choice_label' => 'name',
                'label' => 'Class: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('AppBundle:Year', 'y')
                        ->where('y.active=:active')
                        ->orderBy('p.ordinal', 'ASC')
                        ->setParameter('active', true);
                }
            ))
            ->add('teacher', 'entity', array(
                'multiple' => false,
                'class' => 'AppBundle:User',
                'label' => 'Teacher: ',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.roles', 'r')
                        ->where('r.id = :id')
                        ->orderBy('u.lastName', 'ASC')
                        ->setParameter('id', 2);
                },
            )) 
            ->add('meetingLink', 'text', array('label' => 'Meeting Link:', 'required' => false))
        	->add('meetingPassword', 'text', array('label' => 'Meeting Password:', 'required' => false ))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $lesson = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($lesson);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/lesson.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/lesson/delete/{id}")
     * @ParamConverter("lesson", class="AppBundle:Lesson")
     */
    public function delete($lesson)
    {
        if(!isset($lesson)) {
            return new Response("Lesson not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($lesson);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/lesson", name="app_lesson_display")
     */       
    public function display() {
        $lessons = $this->getDoctrine()
        ->getRepository('AppBundle:Lesson')
        ->createQueryBuilder('l')
        ->join('l.classOfStudents', 'cl')
        ->join('cl.year', 'y')
        ->where('y.active=true')
        ->getQuery()->execute();
        return $this->render('views/lesson.html.twig',  array('lessons' => $lessons));
    } 
    
    /**
     * @Route("/admin/lesson/print", name="app_lesson_print")
     */
    public function displayPrint() {
    	$classesForLesson = $this->getDoctrine()
    	->getRepository('AppBundle:Lesson')
    	->createQueryBuilder('l')
    	->join('l.classOfStudents', 'cl')
        ->join('cl.year', 'y')
    	->orderBy('cl.ordinal')
    	->groupBy('l.classOfStudents')
    	->where('y.active=true')
    	->getQuery()->execute();
    	
    	$lessons = $this->getDoctrine()
    	->getRepository('AppBundle:Lesson')
    	->createQueryBuilder('l')
    	->join('l.classOfStudents', 'cl')
    	->join('cl.year', 'y')
    	->join('l.period', 'p')
    	->orderBy('p.ordinal')
    	->addOrderBy('cl.ordinal')
    	->where('y.active=true')
    	->getQuery()->execute();
    	
    	return $this->render('report/lessonPrint.html.twig',  array(
    			'classesForLesson' => $classesForLesson,
    			'lessons' => $lessons
    			
    	));
    }
}