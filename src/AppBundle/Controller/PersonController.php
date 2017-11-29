<?php
// src/AppBundle/Controller/PersonController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Person;
use AppBundle\Entity\Address;
use AppBundle\Form\Type\ProfileEditType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * 
 */
class PersonController extends Controller
{
    /**
     * 
     * 
     * @Route("/personal", name="personal_info")
     * 
     * 
     */
    public function showPersonalInfoAction(Request $request) {
        
        $user=$this->getUser(); 
                
        $person=$user->getPerson();
        
        if(is_null($person))
        {  
            $person=$this->createPersonAction();
            $user->setPerson($person);
        }
        
        $privatAddress=$person->getPrivatAddress();
        $officialAddress=$person->getOfficialAddress();
        
        if(is_null($privatAddress))
        {  
            $privatAddress=new Address();
            $person->setPrivatAddress($privatAddress);
        }
        
        if(is_null($officialAddress))
        {  
            $officialAddress=new Address();
            $person->setOfficialAddress($officialAddress);
        }
        
        $originalDuties = new ArrayCollection();
        
        // Create an ArrayCollection of the current PersonOnDuty objects in the database
        foreach ($person->getDuties() as $duty) {
            $originalDuties->add($duty);
        }

        $form = $this->createForm(ProfileEditType::class, $person);
        $form->add('create', SubmitType::class, array('label' => 'forms.update', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')));            
        $form->handleRequest($request);

        if ($form->isValid() and $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person->getOfficialAddress());
            $em->persist($person->getPrivatAddress());
            // remove the relationship between the Person and the PersonOnDuty
            foreach ($originalDuties as $duty) {
                if (false === $person->getDuties()->contains($duty)) {
                    // remove the PersonOnDuty from the Person
                    $person->removeDuty($duty);
                    //remove duty from DB
                    $em->remove($duty);          
                }
            }
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute("check_permission");
        }
        
        return $this->render('AppBundle:User:profileEdit.html.twig', array(
          'form' => $form->createView(),
        )); 
        
    }
    
    
    public function createPersonAction()
    {
        $person=new Person();
        $address=new Address();
        $person->setAddress($address);
        
        return $person;   
    }
    
    
}

