<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User2program
 *
 * @ORM\Table(name="user2program", indexes={@ORM\Index(name="fk_user2program_user_idx", columns={"user_id"}), @ORM\Index(name="fk_user2program_program_idx", columns={"program_id"})})
 * @ORM\Entity
 */
class User2program
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
     * @ORM\Column(name="finished", type="boolean", nullable=true)
     */
    private $finished=FALSE;


    /**
     * @var boolean
     *
     * @ORM\Column(name="diploma", type="boolean", nullable=true)
     */
    private $diploma=FALSE;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="programs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Program
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Program",inversedBy="participants")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="program_id", referencedColumnName="id")
     * })
     */
    private $program;



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
     * Set finished
     *
     * @param boolean $finished
     *
     * @return User2program
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return boolean
     */
    public function getFinished()
    {
        return $this->finished;
    }


    /**
     * Set diploma
     *
     * @param boolean $diploma
     *
     * @return User2program
     */
    public function setDiploma($diploma)
    {
        $this->diploma = $diploma;

        return $this;
    }

    /**
     * Get diploma
     *
     * @return boolean
     */
    public function getDiploma()
    {
        return $this->diploma;
    }

    

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return User2program
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

    /**
     * Set program
     *
     * @param \AppBundle\Entity\Program $program
     *
     * @return User2program
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
}
