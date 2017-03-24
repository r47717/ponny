<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class TaskRepository extends EntityRepository 
{

	public function getTasks($showUncompletedOnly, $showCategory, $showHighPriorityOnly, 
        $orderBy = 'task', $order = 'ASC') 
    {
		$qb = $this->getEntityManager()->createQueryBuilder();
        
        $qb->select(['task'])->from('AppBundle:Task', 'task');

        if ($showUncompletedOnly) {
        	$qb->where('task.completed = 0');
        }

        if ($showHighPriorityOnly) {
            $qb->andWhere('task.priority < 3');
        }

        if ($showCategory != 0) {
        	$qb
        	    ->leftJoin('task.categories', 'cat')
        	    ->andWhere('cat.id = :showCat')
        	    ->setParameter('showCat', $showCategory);	
        }

        $qb->orderBy('task.' . $orderBy, $order);
           
        return $qb->getQuery()->getResult();
	}


    public function getSearchItems($search_str) {

        $entities = $this->findAll();

        $items = [];
        foreach ($entities as $task) {
            if (strpos($task->getTask(), $search_str) !== false ) {
                $items[] = $task;
            }
        }

        return $items;
    }

}

