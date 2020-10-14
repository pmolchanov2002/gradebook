<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Model\Notification;

use Doctrine\ORM\EntityManager;

class ParentScheduleNotificationCommand extends ContainerAwareCommand
{
	protected $em;
	
	protected function configure()
	{
		$this->setName('gradebook:parent_schedule')
		->setDescription('Send parent schedule event notifications by e-mail');
	}
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->em = $this->getContainer()->get('doctrine')->getManager();
		$this->sendEvents($output);
	}
	
	private function sendEvents(OutputInterface $output) {

		$users = $this->em
		->getRepository('AppBundle:User')
        ->createQueryBuilder('u')
        ->join('u.roles', 'r')
        ->where('u.active=true')
        ->andWhere('r.role=:role')
        ->andWhere('u.email is not NULL')
        ->orderBy('u.lastName', 'ASC')
		->setParameter('role', 'ROLE_PARENT')
		//->andWhere('u.email=:email')
		//->setParameter('email', 'pmolchanov2002@gmail.com')
        ->getQuery()
        ->execute();

		if(count($users) != 0) {
			foreach($users as $user) {
				$output->writeln("Parent to send: " . $user->getEmail()."(".$user->getLastName()." ".$user->getFirstName().")");
				$this->sendEmail($user,$output);
            }
		}
	}

	private function sendEmail($user,OutputInterface $output) {

		$notification = new Notification();

		$q = $this->em->createQuery("select u from AppBundle:User u left join u.parents pa where pa.id=:id and u.active=true order by u.firstName + u.lastName")
		->setParameter("id", $user->getId());
		$students = $q->getResult();

		$studentLessons = array();

		foreach ($students as $student) {
			
			$q = $this->em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl left join cl.students s left join cl.year y left join l.period p where s.id=:id and y.active=true order by s.firstName + s.lastName, p.ordinal")
			->setParameter("id", $student->getId());
			$lessons = $q->getResult();
			$studentLessons[] = array(
				"student" => $student,
				"lessons" => $lessons
			);
		}

		$templating = $this->getContainer()->get('templating');

		// Create Russian message
		
		$notification->setMessage($templating->render('mail/scheduleParent.html.twig', array(
				"studentLessons" => $studentLessons,
				'user' => $user
		)));

		// Create English message
		
		$notification->setEnglishMessage($templating->render('mail/scheduleParentEnglish.html.twig', array(
				"studentLessons" => $studentLessons,
				'user' => $user
		)));
		
		// Create message to send 
		
		// Create message to send 
		
		$body = $templating->render(
				'mail/parentMessage.html.twig',
				array(
						'notification' => $notification,
						'user' => $user
		));

		$message = \Swift_Message::newInstance()
		->setSubject('Родитель ' . $user . ': Ваше рассписание уроков в школе Св. Сергия Радонежского')
		->setFrom($this->getContainer()->getParameter('mailer_user'))
		->setTo(!empty($user->getRoutingEmail()) ? $user->getRoutingEmail() : $user->getEmail())
		->setBody(
				$body,
				'text/html'
		);
		
		//$output->writeln($body);

		\Swift_Mailer::newInstance(\Swift_MailTransport::newInstance())->send($message);

		return;
	}
}