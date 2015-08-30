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
 *
 * @Route("/stat")
 */
class StatisticsController extends Controller {


    /**
     * @Route("/", name="show_stat")
     */
    public function showStat() {

        $entities = $this->getDoctrine()->getManager()->getRepository('R47717MainBundle:Task')->findAll();

        $all = count($entities);
        $completed = 0; 
        $completed_ontime = 0; 
        $pending = 0;
        $pending_ontime = 0;
        $today = new \DateTime('today');

        foreach ($entities as $entity) {
            if ($entity->getCompleted()) {
                $completed++;
                if ($entity->getCompletedDate() <= $entity->getDue()) {
                    $completed_ontime++;
                }
            } else {
                $pending++;
                if ($entity->getDue() > $today) {
                    $pending_ontime++;
                }
            }
        }

        $completed_overdue = $completed - $completed_ontime;
        $pending_overdue = $pending - $pending_ontime;

        $stat = [
            'all' => $all,
            'completed' => $completed,
            'completed_ontime' => $completed_ontime,
            'completed_overdue' => $completed_overdue,
            'pending' => $pending,
            'pending_ontime' => $pending_ontime, 
            'pending_overdue' => $pending_overdue,
        ];

        return $this->render('R47717MainBundle:Task:stat.html.twig', [
            'stat' => $stat,
        ]);
    }

}