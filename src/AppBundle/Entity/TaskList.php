<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Util\Debug;

/**
 * TaskList
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TaskListRepository")
 */
class TaskList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="noOfItems", type="integer")
     */
    private $noOfItems;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showUncompletedOnly", type="boolean")
     */
    private $showUncompletedOnly;

    /**
     * @var boolean
     *
     * @ORM\Column(name="showHighPriorityOnly", type="boolean")
     */
    private $showHighPriorityOnly;

    /**
     * @var string
     * @ORM\Column(name="sortTasksBy", type="string")
     */
    private $sortTasksBy;

    /**
     * @var integer
     * @ORM\Column(name="showCategory", type="integer", nullable=true)
     */
    private $showCategory;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set noOfItems
     *
     * @param integer $noOfItems
     * @return TaskList
     */
    public function setNoOfItems($noOfItems)
    {
        $this->noOfItems = $noOfItems;

        return $this;
    }

    /**
     * Get noOfItems
     *
     * @return integer 
     */
    public function getNoOfItems()
    {
        return $this->noOfItems;
    }

    /**
     * Set showUncompletedOnly
     *
     * @param boolean $showUncompletedOnly
     * @return TaskList
     */
    public function setShowUncompletedOnly($showUncompletedOnly)
    {
        $this->showUncompletedOnly = $showUncompletedOnly;

        return $this;
    }

    /**
     * Get showUncompletedOnly
     *
     * @return boolean 
     */
    public function getShowUncompletedOnly()
    {
        return $this->showUncompletedOnly;
    }

    /**
     * Set showHighPriorityOnly
     *
     * @param boolean $showHighPriorityOnly
     * @return TaskList
     */
    public function setShowHighPriorityOnly($showHighPriorityOnly)
    {
        $this->showHighPriorityOnly = $showHighPriorityOnly;

        return $this;
    }

    /**
     * Get showHighPriorityOnly
     *
     * @return boolean 
     */
    public function getShowHighPriorityOnly()
    {
        return $this->showHighPriorityOnly;
    }

    /**
     * Set sortTasksBy
     *
     * @param integer $sortTasksBy
     * @return TaskList
     */
    public function setSortTasksBy($sortTasksBy)
    {
        $this->sortTasksBy = $sortTasksBy;

        return $this;
    }

    /**
     * Get sortTasksBy
     *
     * @return integer 
     */
    public function getSortTasksBy()
    {
        return $this->sortTasksBy;
    }

    /**
     * Set showCategory
     *
     * @param integer $showCategory
     * @return TaskList
     */
    public function setShowCategory($showCategory)
    {
        $this->showCategory = $showCategory;

        return $this;
    }

    /**
     * Get showCategory
     *
     * @return integer
     */
    public function getShowCategory()
    {
        return $this->showCategory;
    }
}
