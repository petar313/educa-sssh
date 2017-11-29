<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teacher2openCourse
 *
 * @ORM\Table(name="teacher2open_course", indexes={@ORM\Index(name="fk_user_idx", columns={"id_user"}),  @ORM\Index(name="fk_t2o_open_course_idx", columns={"id_open_course"})})
 * @ORM\Entity
 */
class Teacher2openCourse
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="myOpenCourses")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;


    /**
     * @var \AppBundle\Entity\OpenCourse
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OpenCourse",inversedBy="trainers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_open_course", referencedColumnName="id")
     * })
     */
    private $idOpenCourse;



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
     * @return Teacher2openCourse
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
     * Set idOpenCourse
     *
     * @param \AppBundle\Entity\OpenCourse $idOpenCourse
     *
     * @return Teacher2openCourse
     */
    public function setIdOpenCourse(\AppBundle\Entity\OpenCourse $idOpenCourse = null)
    {
        $this->idOpenCourse = $idOpenCourse;

        return $this;
    }

    /**
     * Get idOpenCourse
     *
     * @return \AppBundle\Entity\OpenCourse
     */
    public function getIdOpenCourse()
    {
        return $this->idOpenCourse;
    }
}
