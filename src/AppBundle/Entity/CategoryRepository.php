<?php
namespace AppBundle\Entity;
use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository {

	public function getNames() {
		$names = [];
		$entities = $this->findAll();
		foreach ($entities as $entity) {
			$names[$entity->getId()] = $entity->getName();
		}

		return $names;
	}

	public function entityByName($name) {
		return $this->findOneByName($name);
	}

	public function tasksCount() {
		$list = [];

		$entities = $this->findAll();
		foreach ($entities as $item) {
			$list[] = [
				'obj' => $item,
				'count' => count($item->getTasks()),
			];
		}

		return $list;
	}

}