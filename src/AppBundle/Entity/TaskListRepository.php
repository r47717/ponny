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
	}

    public function getShowUncompletedOnly() {
		$entity = $this->findAll()[0];
        return $entity->getShowUncompletedOnly();
    }

	public function setShowUncompletedOnly($flag) {
		$entity = $this->findAll()[0];
        $entity->setShowUncompletedOnly($flag);
    }

    public function getShowHighPriorityOnly() {
		$entity = $this->findAll()[0];
        return $entity->getShowHighPriorityOnly();
    }

	public function setShowHighPriorityOnly($flag) {
		$entity = $this->findAll()[0];
        $entity->setShowHighPriorityOnly($flag);
    }

    public function getShowCategory() {
		$entity = $this->findAll()[0];
        return $entity->getShowCategory();
    }

	public function setShowCategory($name) {
		$entity = $this->findAll()[0];
        $entity->setShowCategory($name);
    }

}