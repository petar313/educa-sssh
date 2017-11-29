<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Teacher2course
 *
 * @ORM\Table(name="teacher2course", indexes={@ORM\Index(name="fk_user_idx", columns={"id_user"}), @ORM\Index(name="fk_course_idx", columns={"id_course"})})
 * @ORM\Entity
 */
class Teacher2course
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
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="myCourses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     * 
     * @Assert\NotBlank(message="course.teachernotblank", groups={"course_type"})
     */
    private $idUser;

    /**
     * @var \AppBundle\Entity\Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="trainers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_course", referencedColumnName="id")
     * })
     */
    private $idCourse;



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
     * Set idUser
     *
     * @param \AppBundle\Entity\User $idUser
     *
     * @return Teacher2course
     */
    public function setIdUser(\AppBundle\Entity\User $idUser = null)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idCourse
     *
     * @param \AppBundle\Entity\Course $idCourse
     *
     * @return Teacher2course
     */
    public function setIdCourse(\AppBundle\Entity\Course $idCourse = null)
    {
        $this->idCourse = $idCourse;

        return $this;
    }

    /**
     * Get idCourse
     *
     * @return \AppBundle\Entity\Course
     */
    public function getIdCourse()
    {
        return $this->idCourse;
    }
}
