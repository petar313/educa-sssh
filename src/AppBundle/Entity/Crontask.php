<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crontask
 *
 * @ORM\Table(name="crontask")
 * @ORM\Entity
 */
class Crontask
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="commands", type="text", length=65535, nullable=false)
     */
    private $commands;

    /**
     * @var integer
     *
     * @ORM\Column(name="interval", type="integer", nullable=false)
     */
    private $interval;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastrun", type="datetime", nullable=false)
     */
    private $lastrun;



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
     * Set name
     *
     * @param string $name
     *
     * @return Crontask
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set commands
     *
     * @param string $commands
     *
     * @return Crontask
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * Get commands
     *
     * @return string
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Set interval
     *
     * @param integer $interval
     *
     * @return Crontask
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * Get interval
     *
     * @return integer
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * Set lastrun
     *
     * @param \DateTime $lastrun
     *
     * @return Crontask
     */
    public function setLastrun($lastrun)
    {
        $this->lastrun = $lastrun;

        return $this;
    }

    /**
     * Get lastrun
     *
     * @return \DateTime
     */
    public function getLastrun()
    {
        return $this->lastrun;
    }
}
