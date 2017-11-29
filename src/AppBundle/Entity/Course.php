<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Course
 *
 * @ORM\Table(name="course", uniqueConstraints={@ORM\UniqueConstraint(name="code_course_UNIQUE", columns={"code_course"})}, indexes={@ORM\Index(name="fk_course2program_idx", columns={"program_id"})})
 * @ORM\Entity
 * @UniqueEntity("codeCourse",message="This port is already in use on that host.")
 * @UniqueEntity("name",message="This port is already in use on that host.")
 */
class Course
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
     * @ORM\Column(name="code_course", type="string", length=45, nullable=false)
     * @Assert\NotBlank(message="course.codenotblank", groups={"course_type"})
     */
    private $codeCourse;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="course.namenotblank", groups={"course_type"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="target_group", type="string", length=255, nullable=true)
     */
    private $targetGroup;

    /**
     * @var string
     *
     * @ORM\Column(name="outcomes", type="text", length=65535, nullable=true)
     */
    private $outcomes;


    /**
     * @var string
     *
     * @ORM\Column(name="duration_hours", type="string", length=8, nullable=true)
     */
    private $durationHours;

    /**
     * @var string
     *
     * @ORM\Column(name="duration_days", type="string", length=8, nullable=true)
     */
    private $durationDays;

    /**
     * @var \AppBundle\Entity\Program
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Program",inversedBy="courses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     * })
     */
    private $program;


    /**
     * @ORM\OneToMany(targetEntity="Teacher2course", mappedBy="idCourse", cascade={"persist", "remove"} )
     * @Assert\Valid();
     */
    private $trainers;

    
    /**
     * @ORM\OneToMany(targetEntity="Material", mappedBy="course", cascade={"persist", "remove"} )
     * @Assert\Valid();
     */
    private $materials;
    

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
     * Set codeCourse
     *
     * @param string $codeCourse
     *
     * @return Course
     */
    public function setCodeCourse($codeCourse)
    {
        $this->codeCourse = $codeCourse;

        return $this;
    }

    /**
     * Get codeCourse
     *
     * @return string
     */
    public function getCodeCourse()
    {
        return $this->codeCourse;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Course
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
     * Set description
     *
     * @param string $description
     *
     * @return Course
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
     * Set content
     *
     * @param string $content
     *
     * @return Course
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set targetGroup
     *
     * @param string $targetGroup
     *
     * @return Course
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
     * Set outcomes
     *
     * @param string $outcomes
     *
     * @return Course
     */
    public function setOutcomes($outcomes)
    {
        $this->outcomes = $outcomes;

        return $this;
    }

    /**
     * Get outcomes
     *
     * @return string
     */
    public function getOutcomes()
    {
        return $this->outcomes;
    }

    
    /**
     * Set durationHours
     *
     * @param string $durationHours
     *
     * @return Course
     */
    public function setDurationHours($durationHours)
    {
        $this->durationHours = $durationHours;

        return $this;
    }

    /**
     * Get durationHours
     *
     * @return string
     */
    public function getDurationHours()
    {
        return $this->durationHours;
    }

    /**
     * Set durationDays
     *
     * @param string $durationDays
     *
     * @return Course
     */
    public function setDurationDays($durationDays)
    {
        $this->durationDays = $durationDays;

        return $this;
    }

    /**
     * Get durationDays
     *
     * @return string
     */
    public function getDurationDays()
    {
        return $this->durationDays;
    }

    /**
     * Set program
     *
     * @param \AppBundle\Entity\Program $program
     *
     * @return Course
     */
    public function setProgram(\AppBundle\Entity\Program $program = null)
    {
        $this->program = $program;

        return $this;
    }

    /**
     * Get program
     *
     * @return \AppBundle\Entity\Program
     */
    public function getProgram()
    {
        return $this->program;
    }
    
    public function __toString() {
        return $this->name;
    }
    
    public function __construct() {
        $this->trainers=new \Doctrine\Common\Collections\ArrayCollection();
        $this->materials=new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getTrainers(){
        return $this->trainers;
    }
    
    public function addTrainer($trainer){
        $trainer->setIdCourse($this);
        $this->trainers->add($trainer);
    }
    
    public function removeTrainer($trainer){
        $this->trainers->removeElement($trainer);
    }
    
    public function getMaterials(){
        return $this->materials;
    }
    
    public function addMaterial($material){
        $material->setCourse($this);
        $this->materials->add($material);
    }
    
    public function removeMaterial($material){
        $this->materials->removeElement($material);
    }
}
