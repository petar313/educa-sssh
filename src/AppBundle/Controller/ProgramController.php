<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\InitializableControllerInterface;
use AppBundle\Form\Type\ProgramType;
use AppBundle\Entity\Program;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * Admin controller.
 *
 * @Route("/program")
 */
class ProgramController extends Controller //implements InitializableControllerInterface 
{
    /**
     * program index
     *
     * @Route("/", name="program_index")
     * 
     */
    public function indexAction() {
        
        $em = $this->getDoctrine()->getManager();
        
        $programs = $em->getRepository('AppBundle:Program')->findAll();

        
        return $this->render(
            'AppBundle:Program:index.html.twig',
                array('programs'=>$programs)
        );
    }
    
    /**
     * @Route("/create", name="program_create")
     */
    public function createAction(Request $request)
    {
        $program = new Program();
        
        $form = $this->createCreateForm($program);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($program);
            
            $em->flush();
            
            return $this->redirectToRoute("program_index");
        }
        

        return $this->render(
            'AppBundle:Program:create.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * Creates a form to create a Program.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Program $program)
    {
        $form = $this->createForm(ProgramType::class, $program, array(
            'action' => $this->generateUrl('program_create'),
            'method' => 'POST',
        ));


        $form->add('create', SubmitType::class, array('label' => 'forms.create', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')));
        return $form;
    }

    
    /**
     * Creates a form to edit a Program.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Program $program)
    {
        $form = $this->createForm(ProgramType::class, $program, array(
            'action' => $this->generateUrl('program_edit', array('id' => $program->getId())),
            'method' => 'POST',
        ));

       $form->add('update', SubmitType::class, array('label' => 'forms.update', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')));

        return $form;
    }
    
    /**
     * @Route("/edit/{id}", name="program_edit")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $program = $em->getRepository("AppBundle:Program")->find($id);
        
        if (!$program) {
            $this->addFlash(
                'error',
                'Unable to find Program entity.'           
                );
            return $this->redirectToRoute("error");
        }
        
        $form = $this->createEditForm($program);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            
            $em->persist($program);
            
            $em->flush();
            
            return $this->redirectToRoute("program_index");
        }
        

        return $this->render(
            'AppBundle:Program:edit.html.twig',
            array('form' => $form->createView())
        );
    }
    
    
    /**
     * @Route("/delete/{id}", name="program_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $program = $em->getRepository("AppBundle:Program")->find($id);
        
        if (!$program) {
            $this->addFlash(
                'error',
                'Unable to find Program entity.'           
                );
            return $this->redirectToRoute("error");
        }
        
        $form = $this->createDeleteForm($id);
        
        $form->handleRequest($request);
        
        if ($form->isValid() and $form->isSubmitted()) {
            
            
            $em->remove($program);
            
            $em->flush();
            
            return $this->redirectToRoute("program_index");
        }
        

        return $this->render(
            'AppBundle:Program:delete.html.twig',
            array('form' => $form->createView(),'program'=>$program)
        );
    }
    
    /**
     * Creates a form to delete a Program entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('program_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('delete', SubmitType::class, array('label' => 'forms.delete', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block')))
            ->getForm()
        ;
    }
    
    
    
    

}

