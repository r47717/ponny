<?php
namespace AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class TaskListRepository extends EntityRepository {

	public function getSortTasksBy() {
		return $this->findAll()[0]->getSortTasksBy();
	}

	public function setSortTasksBy($field) {
		$entity = $this->findAll()[0];
		$entity->setSortTasksBy($field);
		$this->flush();
	}

	public function setShowUncompletedOnly($flag) {
		$entity = $this->findAll()[0];
        $entity->setShowUncompletedOnly($flag);
    }

    public function getShowUncompletedOnly() {
		$entity = $this->findAll()[0];
        return $entity->getShowUncompletedOnly();
    }
}