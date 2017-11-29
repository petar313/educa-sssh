<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Material
 *
 * @ORM\Table(name="material")
 * @ORM\Entity
 */
class Material
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * 
     * @Assert\NotBlank(message="material.namenotblank", groups={"course_type"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="blob", nullable=true)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="teacher_materials", type="boolean", nullable=true)
     */
    private $teacherMaterials;

    /**
     * @var string
     *
     * @ORM\Column(name="mimetype", type="string", length=45, nullable=true)
     */
    private $mimetype;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=45, nullable=true)
     */
    private $extension;
    
    /**
     * @var \AppBundle\Entity\Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course",inversedBy="materials")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $course;

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
     * Set name
     *
     * @param string $name
     *
     * @return Material
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
     * Set content
     *
     * @param string $content
     *
     * @return Material
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
     * Set teacherMaterials
     *
     * @param boolean $teacherMaterials
     *
     * @return Material
     */
    public function setTeacherMaterials($teacherMaterials)
    {
        $this->teacherMaterials = $teacherMaterials;

        return $this;
    }

    /**
     * Get teacherMaterials
     *
     * @return boolean
     */
    public function getTeacherMaterials()
    {
        return $this->teacherMaterials;
    }
    
    
    
    /**
     * Set mimetype
     *
     * @param string $mimetype
     *
     * @return Material
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;

        return $this;
    }

    /**
     * Get mimetype
     *
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }
    
    
    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Material
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
    
    /**
     * Set course
     *
     * @param \AppBundle\Entity\Course $course
     *
     * @return Material
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
    
    public function __toString() {
        return $this->name;
    }
    
    
    /**
     * 
     * @Assert\File(
     *     maxSize = "16M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png","application/pdf", "application/x-pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "application/vnd.ms-powerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation", "application/vnd.openxmlformats-officedocument.presentationml.slideshow"},
     *     mimeTypesMessage="material.mimeTypesMessage",
     *     maxSizeMessage="materijal.maxSizeMessage",
     *     groups={"course_type"}
     * )
     */
    protected $file;
    
    /**
     * Sets file.
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file = NULL) {
        $this->file = $file;
    }
    
    /**
     * Get file.
     * 
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * Upload a file.
     */
    public function upload() {
        // File property can be empty.
        if (NULL === $this->getFile()) {
            return;
        }
        
        $stream = fopen($this->getFile(),'rb');
        //we want the real file not a path, so:
        $this->setContent(base64_encode(stream_get_contents($stream)));
        
        //$this->setExtension($this->getFile()->guessExtension());
        $this->setExtension($this->getFile()->getClientOriginalExtension());
        $this->setMimetype($this->getFile()->getMimeType());
                
    }
}
