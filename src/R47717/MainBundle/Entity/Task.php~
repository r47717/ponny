<?php

namespace R47717\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Task
{
    public function __construct() {
        $this->due = new \DateTime('tomorrow');
        $this->startedDate = new \DateTime('today');
        $this->completed = false;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="task", type="string", length=255)
     */
    private $task;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=40, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="due", type="datetime", nullable=true)
     */
    private $due;

    /**
     * @var boolean
     *
     * @ORM\Column(name="completed", type="boolean", nullable=true)
     */
    private $completed;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="completedDate", type="datetime", nullable=true)
     */
    private $completedDate;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startedDate", type="datetime", nullable=true)
     */
    private $startedDate;


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
     * Set task
     *
     * @param string $task
     * @return Task
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set due
     *
     * @param \DateTime $due
     * @return Task
     */
    public function setDue($due)
    {
        $this->due = $due;

        return $this;
    }

    /**
     * Get due
     *
     * @return \DateTime 
     */
    public function getDue()
    {
        return $this->due;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     * @return Task
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set startedDate
     *
     * @param \DateTime $startedDate
     * @return Task
     */
    public function setStartedDate($startedDate)
    {
        $this->startedDate = $startedDate;

        return $this;
    }

    /**
     * Get startedDate
     *
     * @return \DateTime 
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * Set completedDate
     *
     * @param \DateTime $completedDate
     * @return Task
     */
    public function setCompletedDate($completedDate)
    {
        $this->completedDate = $completedDate;

        return $this;
    }

    /**
     * Get completedDate
     *
     * @return \DateTime 
     */
    public function getCompletedDate()
    {
        return $this->completedDate;
    }

    public function isTaskOverdue() {
        return $this->due < new \DateTime('today');
    }

    public function isDueThisMonth($date) {
        return ($this->due->format('m') == $date->format('m'));
    }
}
