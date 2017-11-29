<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User2program;

/**
 * User2course
 *
 * @ORM\Table(name="user2course", indexes={@ORM\Index(name="fk_user2course2user_idx", columns={"user_id"}), @ORM\Index(name="fk_user2course2open_course_idx", columns={"open_course_id"})})
 * @ORM\Entity
 */
class User2course
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
     * @var boolean
     *
     * @ORM\Column(name="registration_fee", type="boolean", nullable=true)
     */
    private $registrationFee;


    /**
     * @var boolean
     *
     * @ORM\Column(name="sertificate", type="boolean", nullable=true)
     */
    private $sertificate;

    /**
     * @var integer
     * 
     * 1. Prijavite se
     * 2. Nije moguće prijavljivanje
     * 3. Završen
     * 4. Odobren
     * 5. Na odobrenju
     * 6. Odbijen
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="participations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\OpenCourse
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OpenCourse",inversedBy="participants")
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
     * Set registrationFee
     *
     * @param boolean $registrationFee
     *
     * @return User2course
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
     * Set sertificate
     *
     * @param boolean $sertificate
     *
     * @return User2course
     */
    public function setSertificate($sertificate)
    {
        $this->sertificate = $sertificate;

        return $this;
    }

    /**
     * Get sertificate
     *
     * @return boolean
     */
    public function getSertificate()
    {
        return $this->sertificate;
    }
    
    /**
     * Set openCourse
     *
     * @param \AppBundle\Entity\OpenCourse $openCourse
     *
     * @return User2course
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

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return User2course
     */
    public function setStatus($status)
    {
        $this->status = $status;
        if($this->getOpenCourse() and $this->getUser()){
            $this->checkProgram();
        }
        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return User2course
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    
    private function getUser2program($programs,$program){
        foreach($programs as $user2program){
            if($user2program->getProgram()==$program){
                return $user2program;
            }
        }
        $user2program=new User2program();
        
        $user2program->setProgram($program);
        $user2program->setDiploma(FALSE);
        $user2program->setFinished(FALSE);
        $this->getUser()->addProgram($user2program);
        return $user2program;
    }
    
    public function checkProgram(){
        $program =  $this->getOpenCourse()->getCourse()->getProgram();
        $this->getUser()->getPerson()->setDipl(FALSE);
        $user2program=$this->getUser2program($this->getUser()->getPrograms(),$program);
        
        if($this->getStatus()==3){
            $myCourses=array();
            
            foreach($this->getUser()->getParticipations() as $participation){
                if($participation->getOpenCourse()->getCourse()->getProgram()==$program and $participation->getStatus()==3){
                    $myCourses[]=$participation->getOpenCourse()->getCourse();
                }
            }
            if(count($myCourses)>=count($program->getCourses()))
            {
                $user2program->setFinished(TRUE);
                if(!$user2program->getDiploma()){
                    $this->getUser()->getPerson()->setDipl(TRUE);
                }
            }
        }else{
            $user2program->setFinished(FALSE);
        }
    }
}
