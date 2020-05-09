<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use AppBundle\Model\Notification;

use Doctrine\ORM\EntityManager;

class TeacherScheduleNotificationCommand extends ContainerAwareCommand
{
	protected $em;
	
	protected function configure()
	{
		$this->setName('gradebook:teacher_schedule')
		->setDescription('Send teacher schedule event notifications by e-mail');
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
		->setParameter('role', 'ROLE_TEACHER')
		//->andWhere('u.email=:email')
		//->setParameter('email', 'pmolchanov2002@gmail.com')
        ->getQuery()
        ->execute();

		if(count($users) != 0) {
			foreach($users as $user) {
				$output->writeln("Teacher to send: " . $user->getEmail()."(".$user->getLastName()." ".$user->getFirstName().")");
				$this->sendEmail($user,$output);
            }
		}
	}

	private function sendEmail($user,OutputInterface $output) {

		$notification = new Notification();

		// Get teacher's schedule from  the database
		$q = $this->em->createQuery("select l from AppBundle:Lesson l left join l.classOfStudents cl left join cl.students s left join cl.year y left join l.period p left join l.teacher t where t.id=:id and y.active=true order by p.ordinal, cl.ordinal, s.lastName")
		->setParameter("id", $user->getId());
		$lessons = $q->getResult();
		
		// Create Russian message

		$templating = $this->getContainer()->get('templating');
		$notification->setMessage($templating->render('mail/scheduleTeacher.html.twig', array(
				"lessons" => $lessons,
				'user' => $user
		)));

		// Create English message
		
		$notification->setEnglishMessage($templating->render('mail/scheduleTeacherEnglish.html.twig', array(
				"lessons" => $lessons,
				'user' => $user
		)));
		
		// Create message to send 
		
		$body = $templating->render(
				'mail/teacherMessage.html.twig',
				array(
						'notification' => $notification,
						'user' => $user
		));

		$message = \Swift_Message::newInstance()
		->setSubject('Учитель ' . $user . ': Ваше рассписание уроков в школе Св. Сергия Радонежского')
		->setFrom($this->getContainer()->getParameter('mailer_user'))
		->setTo($user->getEmail())
		->setBody(
				$body,
				'text/html'
		);
		//$output->writeln($body);

		\Swift_Mailer::newInstance(\Swift_MailTransport::newInstance())->send($message);
		return;
	}
}