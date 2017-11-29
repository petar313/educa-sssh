<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Duty
 *
 * @ORM\Table(name="person_on_duty")
 * @ORM\Entity
 */
class PersonOnDuty
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
      * @ORM\ManyToOne(targetEntity="Person", inversedBy="duties")
      * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;
    
    /**
      * @ORM\ManyToOne(targetEntity="Duty", inversedBy="persons")
      * @ORM\JoinColumn(name="duty_id", referencedColumnName="id")
     */
    private $duty;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created = 'CURRENT_TIMESTAMP';
    

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
     * Set person
     *
     * @param Person $person
     *
     * @return PersonOnDuty
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }
    
    /**
     * Set duty
     *
     * @param Duty $duty
     *
     * @return PersonOnDuty
     */
    public function setDuty($duty)
    {
        $this->duty = $duty;

        return $this;
    }

    /**
     * Get duty
     *
     * @return Duty
     */
    public function getDuty()
    {
        return $this->duty;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return PersonOnDuty
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
    
    public function __construct() {
        $this->setCreated(new \DateTime("Now"));
    }
}
