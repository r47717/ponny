<?php

namespace R47717\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use R47717\MainBundle\Entity\Task;
use R47717\MainBundle\Form\TaskType;
use \Doctrine\Common\Util\Debug;

/**
* @Route("/calendar")
*/
class CalendarController extends Controller
{
	/**
	 * @Route("/", name="calendar_show")
	 */
	public function showAction() {

		$entities = $this->getDoctrine()->getManager()->getRepository('R47717MainBundle:Task')
			->findBy(['completed' => 0]);

		$data = [];

		$date = new \DateTime('today');
		for ($i = 0; $i < 6; $i++) {
			$mY_str = $date->format("M'y");
			
			$tasks = [];
			foreach ($entities as $task) {
				if ($task->isDueThisMonth($date)) {
					$tasks[] = $task;
				}
			}

			$data[] = [
				'month' => $mY_str,
				'tasks' => $tasks,
			];			
			$date->add(new \DateInterval('P1M'));
		}

		$overdue = [];
		$today = new \DateTime('today');
		foreach ($entities as $task) {
			if ($task->getDue() < $today) {
				$overdue[] = $task;
			}
		}

		return $this->render('R47717MainBundle:Calendar:index.html.twig', [
			'data' => $data,
			'overdue' => $overdue,
		]);
	}	
}