<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Program
 *
 * @ORM\Table(name="program", uniqueConstraints={@ORM\UniqueConstraint(name="code_program_UNIQUE", columns={"code_program"})})
 * @ORM\Entity
 */
class Program
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
     * @ORM\Column(name="code_program", type="string", length=45, nullable=false)
     */
    private $codeProgram;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="target_group", type="string", length=255, nullable=true)
     */
    private $targetGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true)
     */
    private $color;


    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Course", mappedBy="program", cascade={"persist", "remove"})
     */
    private $courses;
    
    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="User2program", mappedBy="program", cascade={"persist", "remove"})
     */
    private $participants;

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
     * Set codeProgram
     *
     * @param string $codeProgram
     *
     * @return Program
     */
    public function setCodeProgram($codeProgram)
    {
        $this->codeProgram = $codeProgram;

        return $this;
    }

    /**
     * Get codeProgram
     *
     * @return string
     */
    public function getCodeProgram()
    {
        return $this->codeProgram;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Program
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
     * Set targetGroup
     *
     * @param string $targetGroup
     *
     * @return Program
     */
    public function setTargetGroup($targetGroup)
    {
        $this->targetGroup = $targetGroup;

        return $this;
    }

    /**
     * Get targetGroup
     *
     * @return string
     */
    public function getTargetGroup()
    {
        return $this->targetGroup;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Program
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
     * Set color
     *
     * @param string $color
     *
     * @return Program
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }
    
    /**
     * get courses
     * 
     * @return ArrayCollection
     */
    public function getCourses() {
        return $this->courses;
    }
    
    /**
     * Add course
     * 
     * @param \AppBundle\Entity\Course $course
     * @return Program
     */
    public function addCourse($course)
    {
        $course->setProgram($this);
        
        $this->courses->add($course);

        return $this;
    }
    
    /**
     * get participants
     * 
     * @return ArrayCollection
     */
    public function getParticipants() {
        return $this->participants;
    }
    
    /**
     * Add participant
     * 
     * @param \AppBundle\Entity\User2program participant
     * @return Program
     */
    public function addParticipant($participant)
    {
        $participant->setProgram($this);
        
        $this->participants->add($participant);

        return $this;
    }
    
    /**
     * Remove participant
     * 
     * @param \AppBundle\Entity\User2program $participant
     */
    public function removeParticipant($participant)
    {
        $this->participants->removeElement($participant);
    }
    
    /**
     * Remove course
     * 
     * @param \AppBundle\Entity\Course $course
     */
    public function removeCourse($course)
    {
        $this->tasks->removeElement($course);
    }
    
    public function __construct() {
        $this->courses=new ArrayCollection();
        $this->participants=new ArrayCollection();
    }
    
    public function __toString() {
        return $this->name;
    }
    
    public function getCourse(){
        return 1;
    }
}
