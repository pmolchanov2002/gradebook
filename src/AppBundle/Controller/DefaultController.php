<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function welcome(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('views/welcome.html.twig');
    }

    /**
     * @Route("/admin", name="admin_homepage")
     */
    public function welcomeAdmin(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('views/welcome_admin.html.twig');
    }

    /**
     * @Route("/teacher", name="teacher_homepage")
     */
    public function welcomeTeacher(Request $request)
    {
    	if($this->get('security.context')->getToken()->getUser()->getPassword() =='4a7d1ed414474e4033ac29ccb8653d9b') {
    		return $this->redirectToRoute('app_teacher_profile');
    	}
        // replace this example code with whatever you need
        return $this->render('views/teacher/welcome_teacher.html.twig');
    }
}
