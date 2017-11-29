<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Term
 *
 * @ORM\Table(name="term", indexes={@ORM\Index(name="fk_term2open_course_idx", columns={"open_course_id"})})
 * @ORM\Entity
 */
class Term
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish_time", type="time", nullable=true)
     */
    private $finishTime;

    /**
     * @var \AppBundle\Entity\OpenCourse
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OpenCourse", inversedBy="terms")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="open_course_id", referencedColumnName="id")
     * })
     */
    private $openCourse;



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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Term
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Term
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set finishTime
     *
     * @param \DateTime $finishTime
     *
     * @return Term
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    /**
     * Get finishTime
     *
     * @return \DateTime
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * Set openCourse
     *
     * @param \AppBundle\Entity\OpenCourse $openCourse
     *
     * @return Term
     */
    public function setOpenCourse(\AppBundle\Entity\OpenCourse $openCourse = null)
    {
        $this->openCourse = $openCourse;

        return $this;
    }

    /**
     * Get openCourse
     *
     * @return \AppBundle\Entity\OpenCourse
     */
    public function getOpenCourse()
    {
        return $this->openCourse;
    }
    
    
    public function getStart(){
        $merge = new \DateTime($this->getDate()->format('Y-m-d') .' ' .$this->getStartTime()->format('H:i:s'));
        return $merge->getTimestamp()."000"; 
    }
    
    public function getEnd(){
        $merge = new \DateTime($this->getDate()->format('Y-m-d') .' ' .$this->getFinishTime()->format('H:i:s'));
        return $merge->getTimestamp()."000"; 
    }
}
