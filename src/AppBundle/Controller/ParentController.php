<?php

// src/AppBundle/Controller/ParentController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use AppBundle\Model\GradeQuery;

class ParentController extends Controller
{

    private $displayRoute = 'app_parent_display';

    /**
     * @Route("/admin/parent/create")
     */
    public function create(Request $request)
    {
        $user = new User();
        $user->setActive(true);
        $form = $this->createFormBuilder($user)
        	->add('lastName', 'text', array('label' => 'Last Name:'))
        	->add('firstName', 'text', array('label' => 'First Name:'))
        	->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('username', 'text', array('label' => 'Login:'))
            ->add('password', 'repeated', array(
            		'type' => 'password',
            		'invalid_message' => 'Password fields do not match',
            		'first_options' => array('label' => 'Password', 'required' => false),
            		'second_options' => array('label' => 'Repeat Password', 'required' => false),
            ))
           ->add('email', 'text', array('label' => 'Email:', 'required' => false))
           ->add('mobilePhone', 'text', array('label' => 'Phone:', 'required' => false))
           ->add('students', 'entity', array(
           		'label' => 'Parent of students: ',
           		'multiple' => true,
           		'expanded' => true,
           		'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.roles', 'r')
                        ->where('r.role = :role')
                        ->andWhere('u.active = true')
                        ->orderBy('u.lastName')
                        ->setParameter('role', 'ROLE_STUDENT');
                }
           ))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            if($user->getPassword() != '') {
                $user->setPassword(md5($user->getPassword()));
            }
            $roles = $this->getDoctrine()->getRepository('AppBundle:Role')->findOneById(4);
            $user->addRole($roles);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/parent.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admin/parent/edit/{id}", name="app_parent_edit")
     * @ParamConverter("user", class="AppBundle:User")     
     */
    public function edit($user, Request $request)
    {
        if(!isset($user)) {
            return new Response("User not found");
        }       
        $old_password = $user->getPassword();
        
        $form = $this->createFormBuilder($user)
        	->add('lastName', 'text', array('label' => 'Last Name:'))
            ->add('firstName', 'text', array('label' => 'First Name:'))
            ->add('englishName', 'text', array('label' => 'English Name:'))
            ->add('username', 'text', array('label' => 'Login:'))
            ->add('password', 'repeated', array(
            		'type' => 'password',
            		'required' => false,
            		'invalid_message' => 'Password fields do not match',
            		'first_options' => array('label' => 'Password', 'required' => false),
            		'second_options' => array('label' => 'Repeat Password', 'required' => false),
            ))
           	->add('email', 'text', array('label' => 'Email:', 'required' => false))
           	->add('mobilePhone', 'text', array('label' => 'Mobile Phone:', 'required' => false))
           ->add('students', 'entity', array(
           		'label' => 'Parent of students: ',
           		'multiple' => true,
           		'expanded' => true,
           		'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.roles', 'r')
                        ->where('r.role = :role')
                        ->andWhere('u.active = true')
                        ->orderBy('u.lastName')
                        ->setParameter('role', 'ROLE_STUDENT');
                }
           ))
           ->add('roles', 'entity', array(
           		'multiple' => true,
           		'expanded' => true,
           		'class' => 'AppBundle:Role',
           		'choice_label' => 'name',
           		'label' => 'Roles: '
           ))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user = $form->getData();
            if($user->getPassword() != $old_password && $user->getPassword() != '') {
                $user->setPassword(md5($user->getPassword()));
            } else {
                $user->setPassword($old_password);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/parent.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/parent/delete/{id}")
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
        return $this->redirectToRoute($this->displayRoute);
    }

    /**
     * @Route("/admin/parent", name="app_parent_display")
     */              
    public function display() {
        $em = $this->getDoctrine()->getManager();
        $q = $em->createQuery("select u from AppBundle\Entity\User u left join u.roles r where r.role='ROLE_PARENT' and u.active=true");
        $users = $q->getResult();
        return $this->render('views/parent.html.twig',  array('users' => $users));
    }
    
    /**
     * @Route("/admin/parent/grade/{id}", name="app_parent_grade")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function displayReport($user) {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setParentId($user->getId());
    	return $this->render ( 'report/parent/grades.html.twig', array(
    			'gradeResult' => $gradeService->obtainGrades ($query),
    			'parentId' => $user->getId()
    	));
    }
    
    /**
     * @Route("/admin/parent/grade/{id}/print")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function displayPrint($user) {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setParentId($user->getId());
    	return $this->render ( 'report/gradesPrint.html.twig', array(
    			'gradeResult' => $gradeService->obtainGrades ($query),
    	));
    }
    
    /**
     * @Route("/admin/parent/grade/{id}/mail")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function sendGrades($user) {
    	$this->sendEmail($user);
    	return $this->render(
    			'parent/mail/success.html.twig',
    			array(
    					'parents' => array ($user->__toString())
    			)
    	);
    }
   
    private function sendEmail($user) {
    	$gradeService = $this->get('GradeService');
    	$query = new GradeQuery();
    	$query->setParentId($user->getId());
    
    	$message = \Swift_Message::newInstance()
    	->setSubject('St. Sergius School. Grades.')
    	->setFrom('pavel@stsergiuslc.com')
    	->setTo($user->getEmail())
    	->setBody(
    			$this->renderView(
    					'mail/gradesReport.html.twig',
    					array(
    							'gradeResult' => $gradeService->obtainGrades ($query),
    							'user' => $user
    					)
    					),
    			'text/html'
    			);
    	$this->get('mailer')->send($message);
    	return;
    }    
    
}