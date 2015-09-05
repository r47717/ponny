<?php
namespace AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository {


	/**
	 * TODO: this should be improved using DQL and JOIN
	 */
	public function getTasks($showUncompletedOnly, $showCategory, $orderBy = 'task', $order = 'ASC') {
		$qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select(['task'])
           ->from('AppBundle:Task', 'task');

        if ($showUncompletedOnly) {
        	$qb = $qb->where('task.completed = 0');
        }

        if ($showCategory != 0) {
        	$qb = $qb
        	    ->leftJoin('task.categories', 'cat')
        	    ->andWhere('cat.id = :showCat')
        	    ->setParameter('showCat', $showCategory);	
        }

        $qb = $qb
            ->orderBy('task.' . $orderBy, $order);
           
        return $qb->getQuery()->getResult();
	}

}

/* alternative 2: DQL */

/*

$em = $this->getDoctrine()->getManager();
$query = $em->createQuery(
    'SELECT task
    FROM AppBundle:Task task
    LEFT JOIN task.categories cat
    WHERE cat.id = :category AND
    task.completed = :flag
    ORDER BY p.price ASC'
)->setParameter('category', $showCategory)
 ->setParameter('flag', $showUncompletedOnly);

$products = $query->getResult();

//
$query = $em->createQuery("SELECT u FROM User u JOIN u.address a WHERE a.city = 'Berlin'");
$users = $query->getResult();


*/
