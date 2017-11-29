<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="login_UNIQUE", columns={"login"})}, indexes={@ORM\Index(name="fk_user2role_idx", columns={"role_id"}), @ORM\Index(name="fk_user2person_idx", columns={"person_id"})})
 * @ORM\Entity
 * @UniqueEntity(fields="login", message="registration.log_used", groups={"Registration", "Profile", "Administration"})
 */
class User implements AdvancedUserInterface , \Serializable
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
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="login.notblank", groups={"Registration","Administration"})
     *  @Assert\Length(min=6, minMessage="login.tooshort", groups={"Registration","Administration"})
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="email.notblank", groups={"Registration","Administration"})
     * @Assert\Email(message="email.notemail", groups={"Registration","Administration"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="hint", type="string", length=45, nullable=true)
     */
    private $hint;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="activationhash", type="string", length=255, nullable=false)
     */
    private $activationhash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="return_path", type="text", length=65535, nullable=true)
     */
    private $returnPath;

    

    /**
     * @var \AppBundle\Entity\Role
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var \AppBundle\Entity\Person
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     * 
     * @Assert\Valid();
     */
    private $person;

    /**
     * @ORM\OneToMany(targetEntity="Teacher2course", mappedBy="idUser", cascade={"persist", "remove"} )
     */
    private $myCourses;
    
    /**
     * @ORM\OneToMany(targetEntity="Teacher2openCourse", mappedBy="idUser", cascade={"persist", "remove"} )
     */
    private $myOpenCourses;
    
    /**
     * @ORM\OneToMany(targetEntity="User2course", mappedBy="user", cascade={"persist", "remove"} )
     */
    private $participations;
    
    
    /**
     * @ORM\OneToMany(targetEntity="User2program", mappedBy="user", cascade={"persist", "remove"} )
     */
    private $programs;


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
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set hint
     *
     * @param string $hint
     *
     * @return User
     */
    public function setHint($hint)
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * Get hint
     *
     * @return string
     */
    public function getHint()
    {
        return $this->hint;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

   
    /**
     * Set activationhash
     *
     *
     * @return User
     */
    public function setActivationhash()
    {
        $this->activationhash =  uniqid();

        return $this;
    }

    /**
     * Get activationhash
     *
     * @return string
     */
    public function getActivationhash()
    {
        return $this->activationhash;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * Set returnPath
     *
     * @param string $returnPath
     *
     * @return User
     */
    public function setReturnPath($returnPath)
    {
        $this->returnPath = $returnPath;

        return $this;
    }

    /**
     * Get returnPath
     *
     * @return string
     */
    public function getReturnPath()
    {
        return $this->returnPath;
    }


    /**
     * Set role
     *
     * @param \AppBundle\Entity\Role $role
     *
     * @return User
     */
    public function setRole(\AppBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \AppBundle\Entity\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set person
     *
     * @param \AppBundle\Entity\Person $person
     *
     * @return User
     */
    public function setPerson(\AppBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \AppBundle\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }
    
    
    public function __toString() {
        if($this->person)
        {
            return $this->person->getLastname()." ".$this->person->getFirstname()." (".$this->getLogin()." - ".$this->getEmail().") ";
        }
        else
        {
            return $this->login." (".$this->getEmail().") ";
        }
    }
    
    public function getRoles()
    {
        if($this->getRole()->getId()===2)
        {
            return array('ROLE_USER','ROLE_ADMIN','ROLE_ALLOWED_TO_SWITCH');
        }
        elseif($this->getRole()->getId()===3)
        {
            return array('ROLE_USER');
        }else{
            return array('ROLE_USER');
        }
        
    }
    
    public function getUserName()
    {
        return $this->login;
    }
    
    public function getSalt()
    {
        return null;
    }
    
    
    public function eraseCredentials() {
    }
    
    
    public function isAccountNonExpired()
    {
        return $this->isActive;
    }

    public function isAccountNonLocked()
    {
        return $this->isActive;
    }

    public function isCredentialsNonExpired()
    {
        return $this->isActive;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }
    
    
    public function getMyCourses(){
        return $this->myCourses;
    }
    
    public function addMyCourse($course){
        $course->setIdUser($this);
        $this->myCourses->add($course);
    }
    
    public function removeMyCourse($course){
        $this->myCourses->removeElement($course);
    }
    
    public function getMyOpenCourses(){
        return $this->myOpenCourses;
    }
    
    public function addMyOpenCourse($course){
        $course->setIdUser($this);
        $this->myOpenCourses->add($course);
    }
    
    public function removeMyOpenCourse($course){
        $this->myOpenCourses->removeElement($course);
    }
    
    public function getParticipations(){
        return $this->participations;
    }
    
    public function addParticipation($participation){
        $participation->setUser($this);
        $this->participations->add($participation);
        $participation->checkProgram();
    }
    
    public function removeParticipation($participation){
        $this->participations->removeElement($participation);
    }
    
    public function getPrograms(){
        return $this->programs;
    }
    
    public function addProgram($program){
        $program->setUser($this);
        $this->programs->add($program);
    }
    
    public function removeProgram($program){
        $this->programs->removeElement($program);
    }
    

    public function __construct() {
        $this->setHint("");//not sure how tu use this
        $this->setUpdated(new \DateTime("Now"));
        $this->setCreated(new \DateTime("Now"));
        $this->setPerson(new \AppBundle\Entity\Person());
        $this->myCourses=new \Doctrine\Common\Collections\ArrayCollection();
        $this->myOpenCourses=new \Doctrine\Common\Collections\ArrayCollection();
        $this->participations=new \Doctrine\Common\Collections\ArrayCollection();
        $this->programs=new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->login,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }
    
    
    /**
     * User2course where user is accepred or finished
     * 
     * @return \Array
     */
    public function getParticipationCourses(){
        $return=array();
        
        foreach($this->participations as $participation){
            if(in_array($participation->getStatus(), array(3,4))){
            $return[]=$participation->getOpenCourse();
            }
        }
            
        return $return;
    }
    
    /**
     * Teacher2openCourse where user teacher in open course
     * 
     * @return \Array
     */
    public function getTeachingCourses(){
        $return=array();
        
        foreach($this->myOpenCourses as $participation){
            
            $return[]=$participation->getIdOpenCourse();
            
        }
        return $return;
    }
    
    /**
     * Teacher2course where user is teeacher for the course in general
     *  
     * @return \Array
     */
    public function getMyTeachingCourses(){
        $return=array();
        
        foreach($this->myCourses as $participation){
            
            $return[]=$participation->getIdCourse();
            
        }
        return $return;
    }
    
    public function checkNeedsAction(){
        $this->getPerson()->setApp(FALSE);
        $this->getPerson()->setCert(FALSE);
        $this->getPerson()->setFee(FALSE);
        foreach ($this->getParticipations() as $app){
            if($app->getStatus()==5){
                $this->getPerson()->setApp(TRUE);
            }
            if(!$app->getSertificate() and $app->getStatus()==3){
                $this->getPerson()->setCert(TRUE);
            }
            if($app->getOpenCourse()->getRegistrationFee() and !$app->getRegistrationFee() and in_array($app->getStatus(),array(4,3)) ){
                $this->getPerson()->setFee(TRUE);
            }
            $app->checkProgram();
        }
        $this->getPerson()->setNeedsAction();
    }
}
