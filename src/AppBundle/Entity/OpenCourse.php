<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * OpenCourse
 *
 * @ORM\Table(name="open_course", indexes={@ORM\Index(name="fk_open_course2evaluation_idx", columns={"evoluation_id"}), @ORM\Index(name="fk_open_course2address_idx", columns={"address_id"}), @ORM\Index(name="fk_open_course2course_idx", columns={"course_id"})})
 * @ORM\Entity
 */
class OpenCourse
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
     * @ORM\Column(name="category", type="string", length=45, nullable=true)
     */
    private $category;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visibility", type="boolean", nullable=true)
     */
    private $visibility;

    /**
     * @var boolean
     * 
     * 1. planiran
     * 2. potvrdjen
     * 3. otkazan
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $confirmed;

    /**
     * @var boolean
     *
     * @ORM\Column(name="registration_fee", type="boolean", nullable=true)
     */
    private $registrationFee;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var float
     *
     * @ORM\Column(name="evoluation", type="text", length=65535, nullable=TRUE)
     */
    private $evoluation;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="part_min", type="integer")
     */
    private $partMin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="part_max", type="integer")
     */
    private $partMax;

    /**
     * @var \AppBundle\Entity\Address
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     * })
     */
    private $address;

    /**
     * @var \AppBundle\Entity\Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;
    
    
    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Term", mappedBy="openCourse", cascade={"persist", "remove"})
     */
    private $terms;

    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Teacher2openCourse", mappedBy="idOpenCourse", cascade={"persist", "remove"})
     */
    private $trainers;
    
    
    /**
     *
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="User2course", mappedBy="openCourse", cascade={"persist", "remove"})
     */
    private $participants;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;
    

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return OpenCourse
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set category
     *
     * @param string $category
     *
     * @return OpenCourse
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set visibility
     *
     * @param boolean $visibility
     *
     * @return OpenCourse
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get visibility
     *
     * @return boolean
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Set confirmed
     *
     * @param integer $confirmed
     *
     * @return OpenCourse
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return integer
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set registrationFee
     *
     * @param boolean $registrationFee
     *
     * @return OpenCourse
     */
    public function setRegistrationFee($registrationFee)
    {
        $this->registrationFee = $registrationFee;

        return $this;
    }

    /**
     * Get registrationFee
     *
     * @return boolean
     */
    public function getRegistrationFee()
    {
        return $this->registrationFee;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return OpenCourse
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
     * Set start
     *
     * @return OpenCourse
     */
    public function setStart()
    {
        $this->start=NULL;
        foreach($this->getTerms() as $term){
            if($term->getDate()<$this->start or is_null($this->start)){
                
                $this->start=$term->getDate();
                $this->start->setTime($term->getStartTime()->format('H'), $term->getStartTime()->format('i'), $term->getStartTime()->format('s'));
            }
        }
        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }
    
    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return OpenCourse
     */
    public function setEnd()
    {
        $this->end=NULL;
        foreach($this->getTerms() as $term){
            if($term->getDate()>$this->end or is_null($this->end)){
                $this->end=$term->getDate();
            }
        }

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set evoluation
     *
     * @param string $evoluation
     *
     * @return OpenCourse
     */
    public function setEvoluation($evoluation = null)
    {
        $this->evoluation = $evoluation;

        return $this;
    }

    /**
     * Get evoluation
     *
     * @return string
     */
    public function getEvoluation()
    {
        return $this->evoluation;
    }
    
    
    /**
     * Set partMin
     *
     * @param integer $partMin
     *
     * @return OpenCourse
     */
    public function setPartMin($partMin)
    {
        $this->partMin = $partMin;

        return $this;
    }

    /**
     * Get partMin
     *
     * @return integer
     */
    public function getPartMin()
    {
        return $this->partMin;
    }
    
    
    /**
     * Set partMax
     *
     * @param integer $partMax
     *
     * @return OpenCourse
     */
    public function setPartMax($partMax)
    {
        $this->partMax = $partMax;

        return $this;
    }

    /**
     * Get partMax
     *
     * @return integer
     */
    public function getPartMax()
    {
        return $this->partMax;
    }
    

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return OpenCourse
     */
    public function setAddress(\AppBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return OpenCourse
     */
    public function setCourse(\AppBundle\Entity\Course $course = null)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return \AppBundle\Entity\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
    
    /**
     * get terms
     * 
     * @return ArrayCollection
     */
    public function getTerms() {
        return $this->terms;
    }
    
    /**
     * Add term
     * 
     * @param \AppBundle\Entity\Term $term
     * @return Program
     */
    public function addTerm($term)
    {
        $term->setOpenCourse($this);
        
        $this->terms->add($term);

        return $this;
    }
    
    /**
     * Remove term
     * 
     * @param \AppBundle\Entity\Term $term
     */
    public function removeTerm($term)
    {
        $this->terms->removeElement($term);
    }
    
    /**
     * get trainers
     * 
     * @return ArrayCollection
     */
    public function getTrainers() {
        return $this->trainers;
    }
    
    /**
     * Add trainer
     * 
     * @param \AppBundle\Entity\Teacher2openCourse trainer
     * @return OpenCourse
     */
    public function addTrainer($trainer)
    {
        $trainer->setIdOpenCourse($this);
        
        $this->trainers->add($trainer);

        return $this;
    }
    
    /**
     * Remove trainer
     * 
     * @param \AppBundle\Entity\Teacher2openCourse $trainer
     */
    public function removeTrainer($trainer)
    {
        $this->trainers->removeElement($trainer);
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
     * @param \AppBundle\Entity\User2course participant
     * @return OpenCourse
     */
    public function addParticipant($participant)
    {
        $participant->setOpenCourse($this);
        
        $this->participants->add($participant);
        
        $participant->checkProgram();

        return $this;
    }
    
    /**
     * Remove participant
     * 
     * @param \AppBundle\Entity\User2course $participant
     */
    public function removeParticipant($participant)
    {
        $this->participants->removeElement($participant);
    }
    
    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return OpenCourse
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
    
    public function __construct() {
        $this->terms=new ArrayCollection();
        $this->trainers=new ArrayCollection();
        $this->participants=new ArrayCollection();
    }
    
    public function getNameAndAdrress(){
        return $this->getCourse()->getName().", ".$this->getAddress()->getCity();
    }
    
    public function getNumberOfTerms()
    {
        return count($this->getTerms());
    }
    
    public function partCount(){
        $return=0;
        foreach($this->participants as $part){
            if(in_array($part->getStatus(),array(3,4))){
                $return++;
            }
        }
        return $return;
    }
    
    public function kotCount(){
        $return=0;
        foreach($this->participants as $part){
            if(!$part->getRegistrationFee() and in_array($part->getStatus(),array(3,4))){
                $return++;
            }
        }
        return $return;
    }
    
    public function sertCount(){
        $return=0;
        foreach($this->participants as $part){
            if(!$part->getSertificate() and $part->getStatus()==3){
                $return++;
            }
        }
        return $return;
    }
    
    public function __toString() {
        return $this->getCourse()->getName().", ".$this->getAddress()->getCity().", ".$this->getStart()->format('d.m.Y');
    }
}
