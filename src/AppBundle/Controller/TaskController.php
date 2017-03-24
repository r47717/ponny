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
class TaskController extends BaseController
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="task")
     * 
     */
    public function indexAction(Request $request)
    {
        $s = $this->get('session');
        
        $field = $s->get('sortTasksBy', 'id');

        $catList = $this->categories()->getNames();
        $catList[0] = '<All>'; // TODO

        $filter_form = $this->createFormBuilder()
            ->add('uncompletedOnly', 'checkbox', [
                'label' => 'uncompleted only', 
                'attr' => [
                  'required' => false,
                ]
            ])
            ->add('category', 'choice', [
                'choices' => $catList,
                'multiple' => false,
                'expanded' => false,
                'label' => 'Category',
                'placeholder' => false,
            ])
            ->add('highPriorityOnly', 'checkbox', [
                'label' => 'high priority only', 
                'attr' => [
                  'required' => false,
                ],
            ])
            ->add('submit', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn-sm btn-success']])
            ->getForm();

        $filter_form->handleRequest($request);

        if ($filter_form->isSubmitted()) {

            $data = $filter_form->getData();
            $s->set('showUncompletedOnly', $data['uncompletedOnly']);
            $s->set('showHighPriorityOnly', $data['highPriorityOnly']);
            $s->set('showCategory', $data['category']);
            $showUncompletedOnly = $data['uncompletedOnly'];
            $showHighPriorityOnly = $data['highPriorityOnly'];
            $showCategory = $data['category'];
        
        } else {
            $showUncompletedOnly = $s->get('showUncompletedOnly', '0');
            $showHighPriorityOnly = $s->get('showHighPriorityOnly', '0');
            $showCategory = $s->get('showCategory', null);
            $filter_form->setData([
                'uncompletedOnly' => $showUncompletedOnly,
                'highPriorityOnly' => $showHighPriorityOnly,
                'category' => $showCategory,
            ]);
        }

        // get entities using the filters

        $entities = $this->tasks()->getTasks($showUncompletedOnly, $showCategory, $showHighPriorityOnly, $field, 'ASC');

        return $this->render('Task/index.html.twig', [
            'entities' => $entities,
            'filter_form' => $filter_form->createView(),
        ]);
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
        $form   = $this->createForm(new TaskType(), $entity, [
            'action' => $this->generateUrl('task_new'),
        ]);
        $form->add('submit', 'submit', ['label' => 'Create', 'attr' => ['class' => 'btn btn-success']]);
        $form->handleRequest($request);

        if ( $form->isSubmitted() ) {
            
            $entity->setStartedDate(new \DateTime('today'));

            if ( $form->isValid() ) {
                $this->em()->persist($entity);
                $this->em()->flush();

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
        $entity = $this->tasks()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $form = $this->createForm(new TaskType(), $entity, array(
            'action' => $this->generateUrl('task_edit', ['id' => $entity->getId()]),
        ))
            ->add('id', 'text', ['disabled' => true])
            ->add('completed', 'checkbox')
            ->add('update', 'submit', ['label' => 'Update', 'attr' => ['class' => 'btn btn-success']])
            ->add('delete', 'submit', ['label' => 'Delete task', 'attr' => ['class' => 'btn btn-danger pull-right']]);

        $form->handleRequest($request);

        if ($form->isValid()) {

          if ($form->get('delete')->isClicked()) {
              return $this->redirectToRoute('task_delete', ['id' => $entity->getId()]);
          }

          $this->em()->flush();
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
        $entity = $this->tasks()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $this->em()->remove($entity);
        $this->em()->flush();

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
        $entity = $this->tasks()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(true);
        $entity->setCompletedDate(new \DateTime('today'));
        $this->em()->flush();

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
        $entity = $this->tasks()->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $entity->setCompleted(false);
        $this->em()->flush();

        return $this->redirectToRoute('task');
    }


    /**
     * @Route("/task/overdue", name="show_overdue")
     */
    public function showOverdue() {
        $entities = $this->em()->createQuery('SELECT t FROM AppBundle:Task t WHERE t.due < CURRENT_DATE()
            AND t.completed = 0')->getResult();

        return $this->render('Task/overdue.html.twig', [
            'entities' => $entities,
        ]);
    }


    /**
     * @Route("/task/sort/{field}", name="sort_tasks")
     */
    public function sortTaskBy($field) {
        $this->get('session')->set('sortTasksBy', $field);
        return $this->redirectToRoute('task');
    }
}
