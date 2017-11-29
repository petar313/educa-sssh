<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\InitializableControllerInterface;//ovo ukljucujemo kasnije
use AppBundle\Form\Type\CourseType;
use AppBundle\Entity\Course;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Admin controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller //implements InitializableControllerInterface 
{
    /**
     * course index
     *
     * @Route("/", name="course_index")
     * 
     */
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $courses = $em->getRepository('AppBundle:Course')->findAll();
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        
        return $this->render(
            'AppBundle:Course:index.html.twig',
                array('courses'=>$courses,'programs' => $programs,)
        );
    }
    
    /**
     * @Route("/create", name="course_create")
     */
    public function createAction(Request $request)
    {
        $course = new Course();
        
        $form = $this->createCreateForm($course);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            $em = $this->getDoctrine()->getManager();
            
            foreach ($course->getmaterials() as $material) {
                $material->upload();
            }
            
            $em->persist($course);
            
            $em->flush();
            
            return $this->redirectToRoute("course_index");
        }
        

        return $this->render(
            'AppBundle:Course:create.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * Creates a form to create a Course.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Course $course)
    {
        $form = $this->createForm(CourseType::class, $course, array(
            'action' => $this->generateUrl('course_create'),
            'method' => 'POST',
        ));


        $form->add('create', SubmitType::class, array('label' => 'forms.create', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')));
        return $form;
    }

    
    /**
     * Creates a form to edit a Course.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Course $course)
    {
        $form = $this->createForm(CourseType::class, $course, array(
            'action' => $this->generateUrl('course_edit', array('id' => $course->getId())),
            'method' => 'POST',
        ));

       $form->add('update', SubmitType::class, array('label' => 'forms.update', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')));

        return $form;
    }
    
    /**
     * @Route("/edit/{id}", name="course_edit")
     */
    public function editAction(Request $request, $id)
    {
        set_time_limit(0);
        $em = $this->getDoctrine()->getManager();
        
        $course = $em->getRepository("AppBundle:Course")->find($id);
        
        if (!$course) {
            $this->addFlash(
                'error',
                'Unable to find Course entity.'           
                );
            return $this->redirectToRoute("error");
        }
        
        $originalTrainers = new ArrayCollection();
        $originalMaterials = new ArrayCollection();

        // Create an ArrayCollection of the current Teacher2course objects in the database
        foreach ($course->getTrainers() as $trainer) {
            $originalTrainers->add($trainer);
        }
        
        // Create an ArrayCollection of the current Teacher2course objects in the database
        foreach ($course->getmaterials() as $material) {
            $originalMaterials->add($material);
        }
        
        $form = $this->createEditForm($course);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            // remove the relationship between the trainer and the Course
            foreach ($originalTrainers as $trainer) {
                if (false === $course->getTrainers()->contains($trainer)) {
                    // remove the Task from the Tag
                    $course->removeTrainer($trainer);
                    //remove term from DB
                    $em->remove($trainer);          
                }
            }
            
            // remove the relationship between the material and the Course
            foreach ($originalMaterials as $material) {
                if (false === $course->getMaterials()->contains($material)) {
                    // remove the Task from the Tag
                    $course->removeMaterial($material);
                    //remove term from DB
                    $em->remove($material);          
                }
            }
            
            foreach ($course->getmaterials() as $material) {
                $material->upload();
            }
            
            $em->persist($course);
            $em->flush();
            
            return $this->redirectToRoute("course_index");
        }
        

        return $this->render(
            'AppBundle:Course:edit.html.twig',
            array('form' => $form->createView())
        );
    }
    
    
    /**
     * @Route("/delete/{id}", name="course_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $course = $em->getRepository("AppBundle:Course")->find($id);
        
        if (!$course) {
            $this->addFlash(
                'error',
                'Unable to find Course entity.'           
                );
            return $this->redirectToRoute("error");
        }
        
        $form = $this->createDeleteForm($id);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            
            $em->remove($course);
            
            $em->flush();
            
            return $this->redirectToRoute("course_index");
        }
        

        return $this->render(
            'AppBundle:Course:delete.html.twig',
            array('form' => $form->createView(),'course'=>$course)
        );
    }
    
    /**
     * Creates a form to delete a Course entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('course_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, array('label' => 'forms.delete', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')))
            ->getForm()
        ;
    }

}

