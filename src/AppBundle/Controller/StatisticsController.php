<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use R47717\MainBundle\Entity\Task;
use R47717\MainBundle\Form\TaskType;
use \Doctrine\Common\Util\Debug;
use Ob\HighchartsBundle\Highcharts\Highchart;

/**
 *
 * @Route("/stat")
 */
class StatisticsController extends Controller {


    /**
     * @Route("/", name="show_stat")
     */
    public function showStat() {

        $entities = $this->getDoctrine()->getManager()->getRepository('AppBundle:Task')->findAll();

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

        return $this->render('Task/stat.html.twig', [
            'chart' => $this->buildChart($stat),
        ]);
    }

    public function buildChart($data) {
        $series = [ 
            ['name' => 'Total tasks',       'data' => [$data['all']]],
            ['name' => 'Completed',         'data' => [$data['completed']]],
            ['name' => 'Completed on-time', 'data' => [$data['completed_ontime']]],
            ['name' => 'Completed overdue', 'data' => [$data['completed_overdue']]],
            ['name' => 'Pending',           'data' => [$data['pending']]],
            ['name' => 'Pending on-time',   'data' => [$data['pending_ontime']]],
            ['name' => 'Pending overdue',   'data' => [$data['pending_overdue']]],
        ];

        $ob = new Highchart();
        $ob->chart->renderTo('stat-charts');  // The #id of the div where to render the chart
        $ob->chart->type('column');
        $ob->title->text('Task Statistics');
        $ob->xAxis->title(['text'  => "Parameter"]);
        $ob->yAxis->title(['text'  => "Value"]);
        $ob->series($series);

        return $ob;
    }

}