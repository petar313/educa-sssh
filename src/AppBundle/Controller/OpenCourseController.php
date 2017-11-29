<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\OpenCourse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\User2course;
use AppBundle\Entity\User2program;
use AppBundle\Controller\AppBundle\Entity;

/**
 * OpenCourse controller.
 *
 * @Route("/opencourse")
 */
class OpenCourseController extends Controller
{
    /**
     * Lists all OpenCourse entities.
     *
     * @Route("/", name="opencourse_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $courses = $em->getRepository('AppBundle:Course')->findAll();
        $openCourses = $em->getRepository('AppBundle:OpenCourse')->findBy(array(), array('start' => 'DESC'));
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        
        return $this->render('AppBundle:OpenCourse:index.html.twig', array(
            'openCourses' => $openCourses,
            'programs' => $programs,
            'courses' => $courses,
        ));
    }

    /**
     * Creates a new OpenCourse entity.
     *
     * @Route("/new/{courseId}", name="opencourse_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $courseId)
    {
        $em = $this->getDoctrine()->getManager();
        $course=$em->getRepository("AppBundle:Course")->find($courseId);
        $openCourse = new OpenCourse();
        $openCourse->setCourse($course); 
        $address= new \AppBundle\Entity\Address();
        $openCourse->setAddress($address);
        $term = new \AppBundle\Entity\Term();
        $date= new \DateTime();
        $term->setDate($date);
        $term->setStartTime($date);
        $term->setFinishTime($date);
        $openCourse->addTerm($term);
        
        $form = $this->createForm('AppBundle\Form\Type\OpenCourseType', $openCourse, array('course'=>$courseId));
        $form->add('save', SubmitType::class, array(
            'attr' => array('class' => 'btn btn-lg btn-danger btn-block'),
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $openCourse->setStart();
            $openCourse->setEnd();
            $em->persist($openCourse);
            $em->flush();
            
            $this->updateParticipation($em, $openCourse);
            return $this->redirectToRoute('opencourse_show', array('id' => $openCourse->getId()));
        }

        return $this->render('AppBundle:OpenCourse:new.html.twig', array(
            'openCourse' => $openCourse,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Listo of finished courses and programs
     * 
     * @Route("/finished/", name="opencourse_finished")
     */
    public function finishedAction(){
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();

        $finishedCourses = $em->getRepository("AppBundle:User2course")->findBy(array('user' => $user, 'status'=>'3'));
        $finishedPrograms = $em->getRepository("AppBundle:User2program")->findBy(array('user' => $user, 'finished'=>'1'));
        
        return $this->render(
            'AppBundle:OpenCourse:finished.html.twig',
                array('finishedCourses'=>$finishedCourses, 'finishedPrograms'=>$finishedPrograms)
        );
    }

    /**
     * Finds and displays a OpenCourse entity.
     *
     * @Route("/{id}", name="opencourse_show")
     * @Method("GET")
     */
    public function showAction(OpenCourse $openCourse)
    {
        $deleteForm = $this->createDeleteForm($openCourse);

        return $this->render('AppBundle:OpenCourse:show.html.twig', array(
            'openCourse' => $openCourse,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing OpenCourse entity.
     *
     * @Route("/{id}/edit", name="opencourse_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, OpenCourse $openCourse)
    {
        
        $originalTerms = new ArrayCollection();

        // Create an ArrayCollection of the current Term objects in the database
        foreach ($openCourse->getTerms() as $term) {
            $originalTerms->add($term);
        }
        
         $originalTrainers = new ArrayCollection();

        // Create an ArrayCollection of the current Term objects in the database
        foreach ($openCourse->getTrainers() as $trainer) {
            $originalTrainers->add($trainer);
        }
        
         $originalParticipants = new ArrayCollection();

        // Create an ArrayCollection of the current Term objects in the database
        foreach ($openCourse->getParticipants() as $participant) {
            $originalParticipants->add($participant);
        }
        
        $deleteForm = $this->createDeleteForm($openCourse);
        $editForm = $this->createForm('AppBundle\Form\Type\OpenCourseType', $openCourse, array('course'=>$openCourse->getCourse()->getId()));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            // remove the relationship between the tag and the Task
            foreach ($originalTerms as $term) {
                if (false === $openCourse->getTerms()->contains($term)) {
                    // remove the Task from the Tag
                    $openCourse->removeTerm($term);
                    //remove term from DB
                    $em->remove($term);          
                }
            }
            
            // remove the relationship between the tag and the Task
            foreach ($originalTrainers as $trainer) {
                if (false === $openCourse->getTrainers()->contains($trainer)) {
                    // remove the trainer2opencourse from the opencourse
                    $openCourse->removeTrainer($trainer);
                    //remove trainer2opencourse from DB
                    $em->remove($trainer);          
                }
            }
            
            // remove the relationship between the tag and the Task
            foreach ($originalParticipants as $participant) {
                if (false === $openCourse->getParticipants()->contains($participant)) {
                    // remove the trainer2opencourse from the opencourse
                    $openCourse->removeParticipant($participant);
                    //remove trainer2opencourse from DB
                    $em->remove($participant);          
                }
            }
            
            
            $openCourse->setStart();
            $openCourse->setEnd();
            $em->persist($openCourse);
            $em->flush();
            
            $this->updateParticipation($em, $openCourse);

            return $this->redirectToRoute('opencourse_index');
        }

        return $this->render('AppBundle:OpenCourse:edit.html.twig', array(
            'openCourse' => $openCourse,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a OpenCourse entity.
     *
     * @Route("/{id}", name="opencourse_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, OpenCourse $openCourse)
    {
        $form = $this->createDeleteForm($openCourse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($openCourse);
            $em->flush();
        }

        return $this->redirectToRoute('opencourse_index');
    }

    /**
     * Creates a form to delete a OpenCourse entity.
     *
     * @param OpenCourse $openCourse The OpenCourse entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(OpenCourse $openCourse)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('opencourse_delete', array('id' => $openCourse->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    
    public function statusAction(OpenCourse $openCourse){
        $user=$this->getUser();
        
        $em = $this->getDoctrine()->getManager();
        
        if(in_array($user->getRole()->getId(),array(2,3))){
            if(in_array($openCourse,$user->getTeachingCourses())){
                    $status=$this->status2Text(7);//predaje na ovom konkretnom kursu
                    return $this->render('AppBundle:OpenCourse:_status.html.twig',array('status'=>$status, 'openCourse'=>$openCourse));
            }
            if(in_array($openCourse->getCourse(),$user->getMyTeachingCourses())){
                    $status=$this->status2Text(8);//predaje na ovom kursu ali ne na konkretnom dogadjaju
                    return $this->render('AppBundle:OpenCourse:_status.html.twig',array('status'=>$status, 'openCourse'=>$openCourse));
            }
        }
        
        
        $status=1;
        $thisOpenCourse = $em->getRepository("AppBundle:User2course")->findOneBy(array('user' => $user, 'openCourse'=>$openCourse));
        
        if($thisOpenCourse){
            $status=$this->status2Text($thisOpenCourse->getStatus());
        }else{
            $qb = $em->createQueryBuilder();
            $qb->select( "a" )
                ->from('AppBundle\Entity\User2course', 'a') 
                ->join('a.openCourse','b')
                ->join('b.course','c')
                ->where($qb->expr()->eq('a.user',$user->getId()))
                ->andWhere($qb->expr()->eq('c.id',$openCourse->getCourse()->getId()));
            $query = $qb->getQuery();
            $otherOpenCourse = $query->getResult();
            foreach($otherOpenCourse as $course){
                $status=$this->checkStatus($status,$course->getStatus());
            }
            $status=$this->status2Text($status);
        }
        
        return $this->render('AppBundle:OpenCourse:_status.html.twig',array('status'=>$status, 'openCourse'=>$openCourse));
    }
    
    /**
     * User applicate to open course
     * 
     * @param OpenCourse $openCourse
     * @Route("/applicate/{openCourse}", name="opencourse_applicate")
     */
    public function applicateAction(OpenCourse $openCourse){
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $user2course=new User2course();
        
        
        //provera da li je već u programu
        $program=$openCourse->getCourse()->getProgram();
        $user2program = $em->getRepository("AppBundle:User2program")->findOneBy(array('user' => $user, 'program'=>$program));
        
        if(!$user2program){
            $user2program=new User2program();
            $user2program->setUser($user);
            $user2program->setProgram($program);
            $em->persist($user2program);
            $em->flush();
        }
        
        $user2course->setOpenCourse($openCourse);
        $user2course->setUser($user);
        $user2course->setStatus(5);//5=na odobrenju
        $user2course->setSertificate(FALSE);
        $user2course->setRegistrationFee(FALSE);
        $user2course->checkProgram();
        $em->persist($user2course);
        
        $user->getPerson()->setApp(TRUE);
        //$user->getPerson()->setCert(TRUE);//tek kad završi kurs
        if($openCourse->getRegistrationFee()){
            $user->getPerson()->setFee(TRUE);
        }
        
        

        
        $em->flush();
            
        return $this->redirectToRoute('homepage_member', array('showMessage'=>TRUE));
    }
    
    private function status2Text($status){
        switch($status){
                    case 1:
                        return "Prijavite se";
                    case 2:
                        return "Nije moguće prijavljivanje";
                    case 3:
                        return "Završen";
                    case 4:
                        return "Odobren";
                    case 5: 
                        return "Na odobrenju";
                    case 6: 
                        return "Odbijen";
                    case 7: 
                        return "Moj tečaj";
                    case 8: 
                        return "Nije moguće prijavljivanje";
                }
    }
    
    private function checkStatus($status1,$status2){
        if($status1==3){
            return 3;
        }
        if($status1==2){
            return 2;
        }
        
        switch($status2){
                    case 1:
                        return 1;
                    case 2:
                        return 2;
                    case 3:
                        return 3;
                    case 4:
                        return 2;
                    case 5: 
                        return 2;
                    case 6: 
                        return 1;
                }
    }
    
    public function finishedWidgetAction($status){
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();

        $finished = $em->getRepository("AppBundle:User2course")->findBy(array('user' => $user, 'status'=>$status));
        
        return $this->render(
            'AppBundle:OpenCourse:_finished.html.twig',
                array('finished'=>$finished,'status'=>$status)
        );
    }
    
    
    public function participantsAction($openCourse){
        $em = $this->getDoctrine()->getManager();

        $participants = $em->getRepository("AppBundle:User2course")->findBy(array('openCourse' => $openCourse));
        
        return $this->render(
            'AppBundle:OpenCourse:_participants.html.twig',
                array('participants'=>$participants)
        );
    }
    
    public function updateParticipation($em, OpenCourse $openCourse){
        if($openCourse->getConfirmed()!=3){//if not canceled
            
        foreach($openCourse->getParticipants() as $part){
            $part->getUser()->checkNeedsAction();
            
            //$part->getUser()->getPerson()->setNeedsAction();
            
            $part->checkProgram();
        }
        $em ->flush();
        }
    }
    
}
