<?php

// src/AppBundle/Controller/MediaController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Media;

use Doctrine\ORM\EntityRepository;

class MediaController extends Controller
{

    private $displayRoute = 'app_media_display';

    /**
     * @Route("/admin/web/media/create")
     */
    public function create(Request $request)
    {
        $media = new Media();
        $form = $this->createFormBuilder($media)
            ->add('description', 'text', array('label' => 'Description:'))
            ->add('path', 'text', array('label' => 'URL:'))
            ->add('type', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:MediaType',
            		'choice_label' => 'name',
            		'label' => 'Type: '))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $media = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/media.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/admin/web/media/upload")
     */
    public function upload(Request $request)
    {
    	$media = new Media();
    	$form = $this->createFormBuilder($media)
    	->add('description', 'text', array('label' => 'Description:'))
    	->add('path', 'file', array('label' => 'File:'))
    	->add('type', 'entity', array(
    			'multiple' => false,
    			'class' => 'AppBundle:MediaType',
    			'choice_label' => 'name',
    			'label' => 'Type: '))
    			->add('save', 'submit', array('label' => 'Create'))
    			->getForm();
    
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$media = $form->getData();
    		$file = $media->getPath();
    		$fileName = md5(uniqid()).'.'.$file->guessExtension();
    		
    		$uploadDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads';
    		$file->move($uploadDir, $fileName);
    		
    		$media->setPath($request->getSchemeAndHttpHost()."/uploads/".$fileName);
    		
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($media);
    		$em->flush();
    		return $this->redirectToRoute($this->displayRoute);
   		}
    
    	return $this->render('forms/media.html.twig', array(
   				'form' => $form->createView(),
    	));
    }

    /**
     * @Route("/admin/web/media/edit/{id}")
     * @ParamConverter("media", class="AppBundle:Media")     
     */
    public function edit($media, Request $request)
    {
        if(!isset($media)) {
            return new Response("Media not found");
        }
        $form = $this->createFormBuilder($media)
            ->add('description', 'text', array('label' => 'Description:'))
            ->add('path', 'text', array('label' => 'URL:'))
            ->add('type', 'entity', array(
            		'multiple' => false,
            		'class' => 'AppBundle:MediaType',
            		'choice_label' => 'name',
            		'label' => 'Type: '))
            ->add('save', 'submit', array('label' => 'Save'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $media = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();
            return $this->redirectToRoute($this->displayRoute);
        }    

        return $this->render('forms/media.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/admin/web/media/delete/{id}")
     * @ParamConverter("media", class="AppBundle:Media")
     */
    public function delete($media)
    {
        if(!isset($media)) {
            return new Response("Media not found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($media);
        $em->flush();
        //return new Response("Class was deleted");
        return $this->redirectToRoute($this->displayRoute);
    }

     /**
     * @Route("/admin/web/media")
     */       
    public function display() {
        $medias = $this->getDoctrine()
        ->getRepository('AppBundle:Media')
        ->findAll();
        return $this->render('views/media.html.twig',  array('medias' => $medias));
    }
}