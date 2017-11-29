<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Person
 *
 * @ORM\Table(name="person", indexes={@ORM\Index(name="fk_persons_gender_idx", columns={"gender"}), @ORM\Index(name="fk_person2privat_address_idx", columns={"privat_address_id"}), @ORM\Index(name="fk_person2official_address_idx", columns={"official_address_id"}),@ORM\Index(name="fk_person2duty_idx", columns={"duty_id"}), @ORM\Index(name="fk_person2syndicate_idx", columns={"syndicate_id"})})
 * @ORM\Entity
 */
class Person
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="firstname.notblank", groups={"Registration","Profile"})
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="lastname.notblank", groups={"Registration","Profile"})
     */
    private $lastname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=8, nullable=false)
     * @Assert\Choice(choices = {"male", "female"}, message = "gender.notblank", groups={"Profile","Registration"})
     */
    private $gender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="card_num", type="string", length=45, nullable=true)
     */
    private $cardNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="send_serificate_to", type="integer", nullable=true)
     */
    private $sendSerificateTo;

    /**
     * @var \AppBundle\Entity\Address
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="official_address_id", referencedColumnName="id")
     * })
     */
    private $officialAddress;

    /**
     * @var \AppBundle\Entity\Address
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Address", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="privat_address_id", referencedColumnName="id")
     * })
     */
    private $privatAddress;
    

    /**
     * @var \AppBundle\Entity\Syndicate
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Syndicate")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="syndicate_id", referencedColumnName="id")
     * })
     */
    private $syndicate;


    /**
     * @var string
     *
     * @ORM\Column(name="employer", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="employer.notblank", groups={"Registration","Profile"})
     */
    private $employer;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="telephone.notblank", groups={"Registration","Profile"})
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=255, nullable=true)
     */
    private $mobile;
    
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="reg", type="boolean", nullable=false)
     */
    private $reg = '1';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="app", type="boolean", nullable=false)
     */
    private $app = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="fee", type="boolean", nullable=false)
     */
    private $fee = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="cert", type="boolean", nullable=false)
     */
    private $cert = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="dipl", type="boolean", nullable=false)
     */
    private $dipl = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="needs_action", type="boolean", nullable=false)
     */
    private $needsAction = '0';
    
    /**
     * @ORM\OneToMany(targetEntity="PersonOnDuty", mappedBy="person", cascade={"persist", "remove"} )
     */
    private $duties;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", length=65535, nullable=true)
     */
    private $comment;

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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Person
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Person
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Person
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Person
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Person
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set cardNum
     *
     * @param string $cardNum
     *
     * @return Person
     */
    public function setCardNum($cardNum)
    {
        $this->cardNum = $cardNum;

        return $this;
    }

    /**
     * Get cardNum
     *
     * @return string
     */
    public function getCardNum()
    {
        return $this->cardNum;
    }

    /**
     * Set sendSerificateTo
     *
     * @param integer $sendSerificateTo
     *
     * @return Person
     */
    public function setSendSerificateTo($sendSerificateTo)
    {
        $this->sendSerificateTo = $sendSerificateTo;

        return $this;
    }

    /**
     * Get sendSerificateTo
     *
     * @return integer
     */
    public function getSendSerificateTo()
    {
        return $this->sendSerificateTo;
    }

    /**
     * Set officialAddress
     *
     * @param \AppBundle\Entity\Address $officialAddress
     *
     * @return Person
     */
    public function setOfficialAddress(\AppBundle\Entity\Address $officialAddress = null)
    {
        $this->officialAddress = $officialAddress;

        return $this;
    }

    /**
     * Get officialAddress
     *
     * @return \AppBundle\Entity\Address
     */
    public function getOfficialAddress()
    {
        return $this->officialAddress;
    }

    /**
     * Set privatAddress
     *
     * @param \AppBundle\Entity\Address $privatAddress
     *
     * @return Person
     */
    public function setPrivatAddress(\AppBundle\Entity\Address $privatAddress = null)
    {
        $this->privatAddress = $privatAddress;

        return $this;
    }

    /**
     * Get privatAddress
     *
     * @return \AppBundle\Entity\Address
     */
    public function getPrivatAddress()
    {
        return $this->privatAddress;
    }
    
    /**
     * Set employer
     *
     * @param string $employer
     *
     * @return Person
     */
    public function setEmployer($employer)
    {
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return string
     */
    public function getEmployer()
    {
        return $this->employer;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Person
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Person
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }



    
    /**
     * Set reg
     *
     * @param boolean $reg
     *
     * @return User
     */
    public function setReg($reg)
    {
        $this->reg = $reg;
        $this->setNeedsAction();
        
        return $this;
    }

    /**
     * Get reg
     *
     * @return boolean
     */
    public function getReg()
    {
        return $this->reg;
    }
    
    
    /**
     * Set app
     *
     * @param boolean $app
     *
     * @return User
     */
    public function setApp($app)
    {
        $this->app = $app;
        $this->setNeedsAction();
        return $this;
    }

    /**
     * Get app
     *
     * @return boolean
     */
    public function getApp()
    {
        return $this->app;
    }
    
    
    /**
     * Set fee
     *
     * @param boolean $fee
     *
     * @return User
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
        $this->setNeedsAction();
        return $this;
    }

    /**
     * Get fee
     *
     * @return boolean
     */
    public function getFee()
    {
        return $this->fee;
    }
    
    /**
     * Set cert
     *
     * @param boolean $cert
     *
     * @return User
     */
    public function setCert($cert)
    {
        $this->cert = $cert;
        $this->setNeedsAction();
        return $this;
    }

    /**
     * Get cert
     *
     * @return boolean
     */
    public function getCert()
    {
        return $this->cert;
    }
    
    /**
     * Set dipl
     *
     * @param boolean $dipl
     *
     * @return User
     */
    public function setDipl($dipl)
    {
        $this->dipl = $dipl;
        $this->setNeedsAction();
        return $this;
    }

    /**
     * Get dipl
     *
     * @return boolean
     */
    public function getDipl()
    {
        return $this->dipl;
    }
    
    /**
     * Set needsAction
     *
     * @return User
     */
    public function setNeedsAction()
    {
        if($this->reg or $this->app or $this->fee or $this->cert or $this->dipl){
            $this->needsAction=TRUE;
        }else{
            $this->needsAction=FALSE;
        }

        return $this;
    }

    /**
     * Get needsAction
     *
     * @return boolean
     */
    public function getNeedsAction()
    {
        return $this->needsAction;
    }
    
    /**
     * Set syndicate
     *
     * @param \AppBundle\Entity\Syndicate $syndicate
     *
     * @return Person
     */
    public function setSyndicate(\AppBundle\Entity\Syndicate $syndicate = null)
    {
        $this->syndicate = $syndicate;

        return $this;
    }

    /**
     * Get syndicate
     *
     * @return \AppBundle\Entity\Syndicate
     */
    public function getSyndicate()
    {
        return $this->syndicate;
    }
    
    public function getDuties(){
        return $this->duties;
    }
    
    public function addDuty($duty){
        $duty->setPerson($this);
        $this->duties->add($duty);
    }
    
    public function removeDuty($duty){
        $this->duties->removeElement($duty);
    }
    
    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Person
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
        $this->setCreated(new \DateTime("Now"));
        $this->setBirthday(new \DateTime("Now"));
        $this->duties = new \Doctrine\Common\Collections\ArrayCollection();
        //by default user needs admins action
        $this->reg=TRUE;
        $this->needsAction=TRUE;
    }
}
