<?php
namespace AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository {

	public function getOverdueTasks($orderBy = 'task', $order = 'ASC') {
		$qb = $this->getEntityManager()->createQueryBuilder();
        $q = $qb->select(['task'])
           ->from('AppBundle:Task', 'task')
           ->orderBy('task.' . $orderBy, $order)
           ->getQuery();
           
        return $q->getResult();
	}

}