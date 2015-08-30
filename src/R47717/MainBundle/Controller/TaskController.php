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
 * Task controller.
 *
 * @Route("/task")
 */
class TaskController extends Controller
{

    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task")
     * 
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('R47717MainBundle:Task')->findAll();

        $filter_form = $this->createFormBuilder()
            ->add('check', 'checkbox', ['label' => 'uncompleted only', 'attr' => [
                'required' => false,
            ]])
            ->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn btn-success']])
            ->getForm()
        ;

        $filter_form->handleRequest($request);

        if ($filter_form->isSubmitted()) {

            $data = $filter_form->getData();
            $uncompleted_only = $data['check'];
            // *** other way: $request->request->check;
            $this->setOption($uncompleted_only);
        
        } else {
            $uncompleted_only = $this->getOption('showUncompletedOnly');
            $filter_form->setData(['check' => $uncompleted_only]);
        }

        return $this->render('R47717MainBundle:Task:index.html.twig', [
            'entities' => $entities,
            'uncompleted_only' => $uncompleted_only, 
            'filter_form' => $filter_form->createView(),
        ]);
    }

    /**
     * Read options from DB
     */
    protected function getOption($option) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:TaskList')->findAll();

        return $entity[0]->getShowUncompletedOnly();
    }

    protected function setOption($value) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:TaskList')->findAll()[0];
        $entity->setShowUncompletedOnly($value);
        $em->persist($entity);
        $em->flush();        
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_new")
     * 
     */
    public function newAction(Request $request)
    {
        $entity = new Task();
        $form   = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_new'),
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', ['label' => 'Create', 'attr' => ['class' => 'btn btn-success']]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {
            
            $entity->setStatus('New task');
            $entity->setCompleted(false);
            $entity->setStartedDate(new \DateTime('today'));

            if ( $form->isValid() ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash('notice', 'New task has been added');
                return $this->redirect($this->generateUrl('task'));
            } else {
                $this->addFlash('error', 'Error: cannot add the new task');            
            }
        }

        return $this->render("R47717MainBundle:Task:new.html.twig", [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }


    /**
     * Edits an existing Task entity.
     *
     * @Route("/edit/{id}", name="task_edit")
     * 
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $form = $this->createFormBuilder($entity)
            ->add('task', 'text')
            ->add('description', 'textarea')
            ->add('completed', 'checkbox')
            ->add('status', 'text')
            ->add('due', 'date')
            ->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn-lg btn-success']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'The task has been updated');
            return $this->redirect($this->generateUrl('task'));
        }

        return $this->render('R47717MainBundle:Task:edit.html.twig', [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]);
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/delete/{id}", name="task_delete")
     * 
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->addFlash('notice', 'The task has been deleted');

        return $this->redirect($this->generateUrl('task'));
    }

    
    /**
     * Marks a Task entity as completed.
     *
     * @Route("/complete/{id}", name="task_mark_complete")
     * 
     */
    public function markCompleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(true);
        $entity->setCompletedDate(new \DateTime('today'));
        $em->flush();

        return $this->redirect($this->generateUrl('task'));
    }
    
    /**
     * Marks a Task entity as uncompleted.
     *
     * @Route("/uncomplete/{id}", name="task_mark_uncomplete")
     * 
     */
    public function markUncompleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('R47717MainBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(false);
        $em->flush();

        return $this->redirect($this->generateUrl('task'));
    }


    /**
     * @Route("/overdue", name="show_overdue")
     */
    public function showOverdue() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->createQuery('SELECT t FROM R47717MainBundle:Task t WHERE t.due < CURRENT_DATE()
            AND t.completed = 0')->getResult();

        return $this->render('R47717MainBundle:Task:overdue.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/stat", name="show_stat")
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


/*public function findProductsExpensiveThan($price)
{
  $em = $this->getEntityManager();
  $qb = $em->createQueryBuilder();

  $q  = $qb->select(array('p'))
           ->from('YourProductBundle:Product', 'p')
           ->where(
             $qb->expr()->gt('p.price', $price)
           )
           ->orderBy('p.price', 'DESC')
           ->getQuery();

  return $q->getResult();
}*/
