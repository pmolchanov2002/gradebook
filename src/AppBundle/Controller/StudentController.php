<?php

// src/AppBundle/Controller/StudentController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;

use AppBundle\Model\GradeQuery;


class StudentController extends Controller
{

    private $displayRoute = 'app_class_displaystudents';
    private $studentAdminRoute = 'app_student_admin';

    /**
     * @Route("/admin/student/create/{id}")
     * @ParamConverter("classOfStudents", class="AppBundle:ClassOfStudents")  
     */
    public function createStudentInClass($classOfStudents, Request $request)
    {
        $user = new User();
        $user->addClass($classOfStudents);
        $user->setActive(true);
        $form = $this->createFormBuilder($user)       
            ->add('firstName', 'text', array('label' => 'First Name:'))
            ->add('lastName', 'text', array('label' => 'Last Name:'))
            ->add('dob', 'date', array('label' => 'Date of birth:', 'years' => range(date('Y'), date('Y') - 100)))
            ->add('email', 'text', array('label' => 'Email:', 'required' => false))      
            ->add('save', 'submit', array('label' => 'Create student'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user->setUsername(md5(rand()), 0, 7);
            $user->setPassword(md5(time()));
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneById(3);
            $user->addRole($roles);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute, array('id' => $classOfStudents->getId()));
        }    

        return $this->render('forms/student.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/admin/student/create")
     */
    public function create(Request $request)
    {
    	$user = new User();
    	$user->setActive(true);
    	$form = $this->createFormBuilder($user)
    	->add('lastName', 'text', array('label' => 'Last Name:'))
    	->add('firstName', 'text', array('label' => 'First Name:'))
    	->add('dob', 'date', array('label' => 'Date of birth:', 'years' => range(date('Y'), date('Y') - 100)))
    	->add('email', 'text', array('label' => 'Email:', 'required' => false))
    	->add('username', 'text', array('label' => 'Login:'))
    	->add('password', 'repeated', array(
    			'type' => 'password',
    			'required' => false,
    			'invalid_message' => 'Password fields do not match',
    			'first_options' => array('label' => 'Password', 'required' => false),
    			'second_options' => array('label' => 'Repeat Password', 'required' => false),
    	))
    	->add('save', 'submit', array('label' => 'Create student'))
    	->getForm();
    
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$user = $form->getData();
    		$em = $this->getDoctrine()->getManager();
    		$user->setUsername(md5(rand()), 0, 7);
    		$user->setPassword(md5(time()));
    		$roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneById(3);
    		$user->addRole($roles);
    		$em->persist($user);
    		$em->flush();
    		return $this->redirectToRoute($this->studentAdminRoute);
    	}
    
    	return $this->render('forms/student.html.twig', array(
    			'form' => $form->createView(),
    	));
    }    

    /**
     * @Route("/admin/student/edit/{id}")
     * @ParamConverter("user", class="AppBundle:User")     
     */
    public function edit($user, Request $request)
    {
        if(!isset($user)) {
            return new Response("Student not found");
        }
        
        $em = $this->getDoctrine()->getManager();
        $oldUser = $em->getRepository('AppBundle:User')->find($user->getId());
        $oldClass = null;
        foreach($oldUser->getClasses() as $classOfStudents) {
        	if($classOfStudents->getYear()->getActive()) {
        		$oldClass = $classOfStudents;
        		break;
        	}
        }
        
        $form = $this->createFormBuilder($user)        
            ->add('firstName', 'text', array('label' => 'First Name:'))
            ->add('lastName', 'text', array('label' => 'Last Name:'))
            ->add('active', 'checkbox', array('label' => 'Active', 'required' => false))          
            ->add('classes', 'entity', array(
            		'multiple' => true,
            		'expanded' => true,
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
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) { 
            $newUser = $form->getData();
            if(count($newUser->getClasses()) >1) {
            	return new Response("Student can be assigned to only one class");
            }
            
            $newClass = null;
            foreach($newUser->getClasses() as $classOfStudents) {
            	if($classOfStudents->getYear()->getActive()) {
            		$newClass = $classOfStudents;
            		break;
            	}
            }
            
            $em->getConnection()->beginTransaction(); // suspend auto-commit
            try {      
            	$em->persist($newUser);
	            if($oldClass != null && $newClass != null) {         
		            $em->createQueryBuilder()
		            	->update('AppBundle:GradeExam', 'g')
		            	->set('g.class', ':newClass')
		            	->where('g.class = :oldClass')
		            	->andWhere('g.student = :student')
		            	->setParameter('newClass', $newClass)
		            	->setParameter('oldClass', $oldClass)
		            	->setParameter('student', $oldUser)
		            	->getQuery()
		            	->execute();
	            }
	            $em->flush();
	            $em->getConnection()->commit();
            } catch (Exception $e) {
            	$em->getConnection()->rollback();
            	throw $e;
            }
            //return new Response('done');
            //return $this->redirectToRoute('app_student_display');
        }    

        return $this->render('forms/student.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/admin/student/delete/{id}")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function delete($user)
    {
    	if(!isset($user)) {
    		return new Response("User not found");
    	}
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($user);
    	$em->flush();
    	//return new Response("Class was deleted");
    	return $this->redirectToRoute($this->studentAdminRoute);
    }

     /**
     * @Route("/admin/student", name="app_student_admin")
     */       
    public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select u from AppBundle\Entity\User u left join u.roles r where r.name='Student' and u.active=true");
        $users = $q->getResult();
        return $this->render('views/student.html.twig',  array('users' => $users));
    }
    
    /**
     * @Route("/admin/student/grade/{id}", name="app_student_grade")
     * @ParamConverter("user", class="AppBundle:User")   
     */
    public function displayReport($user) {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setStudentId($user->getId());
    	return $this->render ( 'report/student/grades.html.twig', array(
    			'gradeResult' => $gradeService->obtainGrades ($query),
    			'studentId' => $user->getId()
    	));
    }
    
    /**
     * @Route("/admin/student/grade/{id}/print")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function displayPrint($user) {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setStudentId($user->getId());
    	return $this->render ( 'report/gradesPrint.html.twig', array(
    			'gradeResult' => $gradeService->obtainGrades ($query),
    	));    	
    }
}