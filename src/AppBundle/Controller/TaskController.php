<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use \Doctrine\Common\Util\Debug;

/**
 * Task controller.
 *
 * @Route("/")
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
        // TODO: init the TalkList table
        $field = $this->getOption('sortTasksBy');

        $em = $this->getDoctrine()->getManager();

        $catList = $em->getRepository('AppBundle:Category')->getNames();
        $catList[0] = '<All>';

        $filter_form = $this->createFormBuilder()
            ->add('uncompletedOnly', 'checkbox', ['label' => 'uncompleted only', 'attr' => [
                'required' => false,
            ]])
            ->add('category', 'choice', [
                'choices' => $catList,
                'multiple' => false,
                'expanded' => false,
                'label' => 'Category',
                'placeholder' => false,
            ])
            ->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn-sm btn-success']])
            ->getForm();

        $filter_form->handleRequest($request);

        if ($filter_form->isSubmitted()) {

            $data = $filter_form->getData();
            $this->setOption('showUncompletedOnly', $data['uncompletedOnly']);
            $this->setOption('showCategory', $data['category']);
            $showUncompletedOnly = $data['uncompletedOnly'];
            $showCategory = $data['category'];
        
        } else {
            $showUncompletedOnly = $this->getOption('showUncompletedOnly');
            $showCategory = $this->getOption('showCategory');
            $filter_form->setData([
                'uncompletedOnly' => $showUncompletedOnly, 
                'category' => $showCategory,
            ]);
        }

        // get entities using the filters

        $entities = $em->getRepository('AppBundle:Task')->getTasks($showUncompletedOnly, $showCategory, $field, 'ASC');

        return $this->render('Task/index.html.twig', [
            'entities' => $entities,
            'filter_form' => $filter_form->createView(),
        ]);
    }

    /**
     * Read options from DB
     */
    protected function getOption($option) {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('AppBundle:TaskList');

        switch ($option) {
            case 'showUncompletedOnly':
                return $rep->getShowUncompletedOnly();
            case 'sortTasksBy':
                return $rep->getSortTasksBy();
            case 'showCategory':
                return $rep->getShowCategory();
        }

        $em->flush();
    }

    protected function setOption($option, $value) {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('AppBundle:TaskList');

        switch ($option) {
            case 'showUncompletedOnly':
                $rep->setShowUncompletedOnly($value);
                break;
            case 'sortTasksBy':
                $rep->setSortTasksBy($value);
                break;
            case 'showCategory':
                $rep->setShowCategory($value);
                break;
        }

        $em->flush();
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/task/new", name="task_new")
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
            
            $entity->setStartedDate(new \DateTime('today'));

            if ( $form->isValid() ) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();

                $this->addFlash('notice', 'Notice: New task has been added');
                return $this->redirectToRoute('task');
            } else {
                $this->addFlash('error', 'Error: cannot add the new task');            
            }
        }

        return $this->render("Task/new.html.twig", [
            'entity' => $entity,
            'form'   => $form->createView(),
        ]);
    }


    /**
     * Edits an existing Task entity.
     *
     * @Route("/task/edit/{id}", name="task_edit")
     * 
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $form = $this->createFormBuilder($entity)
            ->add('id', 'text', ['disabled' => true])
            ->add('task', 'text')
            ->add('priority', 'choice', [
                'choices' => [1=>1,2,3,4,5],
                'multiple' => false,
                'expanded' => false,
                'placeholder' => false,
            ])
            ->add('description', 'textarea')
            ->add('completed', 'checkbox')
            ->add('status', 'text')
            ->add('due', 'date')
            ->add('categories', 'entity', [
                'class' => 'AppBundle:Category',
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn-lg btn-success']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            $this->addFlash('notice', 'Notice: the task has been updated');
            return $this->redirectToRoute('task');
        }

        return $this->render('Task/edit.html.twig', [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]);
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/task/delete/{id}", name="task_delete")
     * 
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $em->remove($entity);
        $em->flush();

        $this->addFlash('notice', 'Notice: the task has been deleted');

        return $this->redirectToRoute('task');
    }

    
    /**
     * Marks a Task entity as completed.
     *
     * @Route("/task/complete/{id}", name="task_mark_complete")
     * 
     */
    public function markCompleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(true);
        $entity->setCompletedDate(new \DateTime('today'));
        $em->flush();

        return $this->redirectToRoute('task');
    }
    
    /**
     * Marks a Task entity as uncompleted.
     *
     * @Route("/task/uncomplete/{id}", name="task_mark_uncomplete")
     * 
     */
    public function markUncompleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(false);
        $em->flush();

        return $this->redirectToRoute('task');
    }


    /**
     * @Route("/task/overdue", name="show_overdue")
     */
    public function showOverdue() {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->createQuery('SELECT t FROM AppBundle:Task t WHERE t.due < CURRENT_DATE()
            AND t.completed = 0')->getResult();

        return $this->render('Task/overdue.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/task/sort/{field}", name="sort_tasks")
     */
    public function sortTaskBy($field) {
        $this->setOption('sortTasksBy', $field);
        return $this->redirectToRoute('task');
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
