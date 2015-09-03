<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskList
 *
 * @ORM\Table()
 * @ORM\Entity
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
     * @var string
     * @ORM\Column(name="sortTasksBy", type="string")
     */
    private $sortTasksBy;

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
}