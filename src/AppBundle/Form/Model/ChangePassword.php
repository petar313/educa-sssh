<?php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{

    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "Password should by at least 6 chars long"
     * )
     * @Assert\NotBlank(message="New password can't be blank")
     */
     protected $newPassword;    

     public function getNewPassword() {
         return $this->newPassword;
     }
     
     public function setNewPassword($newPassword) {
         $this->newPassword=$newPassword;
     }
}