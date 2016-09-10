<?php

// src/AppBundle/Controller/MigrateYearController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Year;
use AppBundle\Model\YearMigration;

class MigrateYearController extends Controller
{

    private $displayRoute = 'app_migrate_year_display';

    /**
     * @Route("/admin/year/migrate")
     */
    public function migrate(Request $request)
    {
        $yearMigration = new YearMigration();
        $form = $this->createFormBuilder($yearMigration)
	        ->add('previousYear', 'entity', array(
	        		'multiple' => false,
	        		'class' => 'AppBundle:Year',
	        		'label' => 'Previous year: '
	        ))
	        ->add('nextYear', 'entity', array(
	        		'multiple' => false,
	        		'class' => 'AppBundle:Year',
	        		'label' => 'Next year: '
	        ))
            ->add('save', 'submit', array('label' => 'Migrate classes'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $yearMigration = $form->getData();
            $em = $this->getDoctrine()->getManager();
            
			$q = $em->getRepository ( 'AppBundle:ClassOfStudents' )
				->createQueryBuilder ('c')
				->join ( 'c.year', 'y' )
				->where ( 'y = :previousYear' )
				->setParameter('previousYear', $yearMigration->getPreviousYear());

			$previousYearClasses = $q->getQuery()->execute();
			foreach ($previousYearClasses as $previousClass) {
				$nextClass = $previousClass->createClone();
				$nextClass->setYear($yearMigration->getNextYear());
				$em->persist($nextClass);
			}
            $em->flush();
            return $this->render('views/yearMigrationSucceded.html.twig');
        }    

        return $this->render('forms/yearMigration.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}