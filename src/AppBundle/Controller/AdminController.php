<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Model\InitializableControllerInterface;
use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Constants\StatusConstants;


/**
 * Admin controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller //implements InitializableControllerInterface 
{
    /**
     * Admin dashboard
     *
     * @Route("/", name="homepage_admin")
     * 
     */
    public function adminDashboardAction() {
        
        $em = $this->getDoctrine()->getManager();        
        
        $now=new \DateTime();

        $qb = $em->createQueryBuilder();
        $qb->select( "a" )
            ->from('AppBundle\Entity\OpenCourse', 'a') 
            ->where($qb->expr()->eq('a.visibility',1))
            ->andWhere($qb->expr()->eq('a.confirmed',2))
            ->andWhere($qb->expr()->gt('a.start',':now'))
            ->setParameter('now',$now);
        $query = $qb->getQuery();
        $confirmed = $query->getResult();
        
        
        $qb2 = $em->createQueryBuilder();
        $qb2->select( "a" )
            ->from('AppBundle\Entity\OpenCourse', 'a') 
            ->where($qb->expr()->eq('a.visibility',1))
            ->andWhere($qb->expr()->eq('a.confirmed',1))
            ->andWhere($qb->expr()->gt('a.start',':now'))
            ->setParameter('now',$now);
        $query2 = $qb2->getQuery();
        $planned = $query2->getResult();
        
        
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        
        
        return $this->render(
            'AppBundle:Default:index.html.twig',
                array('planed'=>$planned,'confirmed'=>$confirmed,'programs'=>$programs)
        );
    }
    
    
    
    /**
     * Admin dashboard
     *
     * @Route("/users", name="app_admin_users_index")
     * 
     */
    public function usersIndexAction() {
        
        $em    = $this->getDoctrine()->getManager();
        $syndicates = $em->getRepository('AppBundle:Syndicate')->findAll();
        $roles = $em->getRepository('AppBundle:Role')->findAll();
        return $this->render('AppBundle:Admin:userIndex.html.twig',array("syndicates"=>$syndicates, 'roles'=>$roles));
        
    }
    
    
    /** Ajax from Controller
    *   ?limit=10&offset=0&order=asc&sort=date
    * @Route("/ajaxusers/", name="get_users_ajax_call")
    *
    */
   public function usersIndexJsonAction(Request $request) {

        if (! $request->isXmlHttpRequest()) {
            $this->addFlash(
                'error',
                'This page is not made for browsers.'           
                );
          return $this->redirectToRoute("error");
        }


        $limit=$request->query->get('limit', '10');
        $offset=$request->query->get('offset', '0');
        $order=$request->query->get('order', 'ASC');
        $sort=$request->query->get('sort', 'id');
        
        

        //$search=$request->query->get('search', '');
        //we dont use serach box for now

        $filter=json_decode($request->query->get('filter', ''),TRUE);
        
                
        $em=$this->getDoctrine()->getManager();

        //for one page of results
        $qb = $em->createQueryBuilder();
        $qb->select( "c" )
            ->from('AppBundle\Entity\User', 'c')
            ->leftJoin('c.person', 'b'); 
        $qb ->where($qb->expr()->gt('c.id',1));//not good
        
        //for total number of rows
        $qb2 = $em->createQueryBuilder();
        $qb2->select( "count(c.id)" )
            ->from('AppBundle\Entity\User', 'c')
            ->leftJoin('c.person', 'b');
        $qb2 ->where($qb2->expr()->gt('c.id',1));//not good
        
       
           
        if (isset($filter["email"])) {
           
            $qb->andWhere($qb->expr()->like('c.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
            $qb2->andWhere($qb->expr()->like('c.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
        }//Adding another, optional condition
        
       if (isset($filter["telephone"])) {
           
            $qb->andWhere($qb->expr()->like('b.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
            $qb2->andWhere($qb->expr()->like('b.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["firstname"])) {
           
            $qb->andWhere($qb->expr()->like('b.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
            $qb2->andWhere($qb->expr()->like('b.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
        }//Adding another, optional condition
        
        
        if (isset($filter["lastname"])) {
           
            $qb->andWhere($qb->expr()->like('b.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
            $qb2->andWhere($qb->expr()->like('b.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["gender"])) {
            
            $filter["gender"]=$this->getGenderDb($filter["gender"]);
           
            $qb->andWhere($qb->expr()->eq('b.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
            $qb2->andWhere($qb->expr()->eq('b.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
        }//Adding another, optional condition
        
        
        
        if (isset($filter["syndicate"])) {
           
            $qb->andWhere($qb->expr()->eq('b.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
            $qb2->andWhere($qb->expr()->eq('b.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
        }//Adding another, optional condition
        
        if (isset($filter["employer"])) {
           
            $qb->andWhere($qb->expr()->like('b.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
            $qb2->andWhere($qb->expr()->like('b.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["isActive"])) {
           
            $qb->andWhere($qb->expr()->eq('c.isActive',':isActive'))
                ->setParameter('isActive',$filter["isActive"]);
            $qb2->andWhere($qb->expr()->eq('c.isActive',':isActive'))
                ->setParameter('isActive',$filter["isActive"]);
        }//Adding another, optional condition
        
        if (isset($filter["reg"])) {
           
            $qb->andWhere($qb->expr()->eq('b.reg',':reg'))
                ->setParameter('reg',$filter["reg"]);
            $qb2->andWhere($qb->expr()->eq('b.reg',':reg'))
                ->setParameter('reg',$filter["reg"]);
        }//Adding another, optional condition
        
        if (isset($filter["app"])) {
           
            $qb->andWhere($qb->expr()->eq('b.app',':app'))
                ->setParameter('app',$filter["app"]);
            $qb2->andWhere($qb->expr()->eq('b.reg',':app'))
                ->setParameter('app',$filter["app"]);
        }//Adding another, optional condition
        
        if (isset($filter["fee"])) {
           
            $qb->andWhere($qb->expr()->eq('b.fee',':fee'))
                ->setParameter('fee',$filter["fee"]);
            $qb2->andWhere($qb->expr()->eq('b.fee',':fee'))
                ->setParameter('fee',$filter["fee"]);
        }//Adding another, optional condition
        
        if (isset($filter["cert"])) {
           
            $qb->andWhere($qb->expr()->eq('b.cert',':cert'))
                ->setParameter('cert',$filter["cert"]);
            $qb2->andWhere($qb->expr()->eq('b.cert',':cert'))
                ->setParameter('cert',$filter["cert"]);
        }//Adding another, optional condition
        
        if (isset($filter["dipl"])) {
           
            $qb->andWhere($qb->expr()->eq('b.dipl',':dipl'))
                ->setParameter('dipl',$filter["dipl"]);
            $qb2->andWhere($qb->expr()->eq('b.dipl',':dipl'))
                ->setParameter('dipl',$filter["dipl"]);
        }//Adding another, optional condition
        
        
        if (isset($filter["needsAction"])) {
           
            $qb->andWhere($qb->expr()->eq('b.needsAction',':needsAction'))
                ->setParameter('needsAction',$filter["needsAction"]);
            $qb2->andWhere($qb->expr()->eq('b.needsAction',':needsAction'))
                ->setParameter('needsAction',$filter["needsAction"]);
        }//Adding another, optional condition
        
        if (isset($filter["role"])) {
           
            $qb->andWhere($qb->expr()->eq('c.role',':role'))
                ->setParameter('role',$filter["role"]);
            $qb2->andWhere($qb->expr()->eq('c.role',':role'))
                ->setParameter('role',$filter["role"]);
        }//Adding another, optional condition
        
        
        
        switch($sort)
        {
            case "firstname": 
            case "lastname": 
            case "telephone": 
            case "reg": 
            case "app": 
            case "fee": 
            case "cert": 
            case "dipl": 
            case "syndicate": 
            case "needsAction":
            case "employer":
            case "birthdate":
                $sort="b.$sort";
                break;
            
            default:
                $sort="c.$sort";
                break;
        }
        
        $qb->orderBy($sort, $order)
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        
        $query = $qb->getQuery();
        $users = $query->getResult();
        
        $query2 = $qb2->getQuery();
        $count = $query2->getResult();
        
        $json=array();
       
        $json["total"]=$count[0][1];
    
        $data=array();
       
        $i=0;
        foreach($users as $user)
        {
           $data[$i]["login"]=$user->getLogin();
           $data[$i]["email"]=$user->getEmail();
            $data[$i]["telephone"]=$user->getPerson()->getTelephone();
           
           $data[$i]["role"]=$user->getRole()->getName();//role is always set
           
           $data[$i]["firstname"]=$user->getPerson()->getFirstname();
           $data[$i]["lastname"]=$user->getPerson()->getLastname();
           $data[$i]["birthdate"]=$user->getPerson()->getBirthday()->format("d.m.Y");
           
           $data[$i]["gender"]=$this->get('translator')->trans($user->getPerson()->getGender());
           
           $data[$i]["syndicate"]=$user->getPerson()->getSyndicate()->getName();
           $data[$i]["employer"]=$user->getPerson()->getEmployer();
           
           $data[$i]["isActive"]="<a href='"
                   .$this->generateUrl('admin_block_user', 
                           array('userId'=>$user->getId() ))
                   ."'><img src='/bundles/app/images/icons/".($user->getIsActive()?"unlocked":"locked").".png"
                    ."' alt=\"".
                   $this->get('translator')->trans('lock.unlock')
                   ."\" title=\"".
                        $this->get('translator')->trans('lock.unlock')
                        ."\" class=\"action-icons\"/>"
                    ."</a> ";
           
           $data[$i]["reg"]=$user->getPerson()->getReg()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["app"]=$user->getPerson()->getApp()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["fee"]=$user->getPerson()->getFee()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["cert"]=$user->getPerson()->getCert()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["dipl"]=$user->getPerson()->getDipl()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["needsAction"]=$user->getPerson()->getNeedsAction()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           
           
           $data[$i]["actions"]="<a href='"
                   .$this->generateUrl('admin_edit_user', 
                           array('id'=>$user->getId() ))
                   ."'>"
                   . "<img src='/bundles/app/images/icons/edit.png')"
                   ."' alt=\"".
                   $this->get('translator')->trans('edit_user')
                   ."\" title=\"".
                        $this->get('translator')->trans('edit_user')
                        ."\" class=\"action-icons\"/>"
                    ."</a> ";
           
           $data[$i]["sendSerificateTo"]=$user->getPerson()->getSendSerificateTo()==1?"Osobna adresa":"Službena adresa";
           
           $data[$i]["officialAddressStreet"]=$user->getPerson()->getOfficialAddress()?$user->getPerson()->getOfficialAddress()->getStreet():"-";
           $data[$i]["officialAddressCity"]=$user->getPerson()->getOfficialAddress()?$user->getPerson()->getOfficialAddress()->getCity():"-";
           $data[$i]["officialAddressZip"]=$user->getPerson()->getOfficialAddress()?$user->getPerson()->getOfficialAddress()->getZip():"-";

           $data[$i]["privatAddressStreet"]=$user->getPerson()->getPrivatAddress()?$user->getPerson()->getPrivatAddress()->getStreet():"-";
           $data[$i]["privatAddressCity"]=$user->getPerson()->getPrivatAddress()?$user->getPerson()->getPrivatAddress()->getCity():"-";
           $data[$i]["privatAddressZip"]=$user->getPerson()->getPrivatAddress()?$user->getPerson()->getPrivatAddress()->getZip():"-";
 
           $i++;
        }
        
        
        $json["rows"]=$data;
       
        return new JsonResponse($json);
    }
    
    
    
    /**
     * @Route("/newuser", name="admin_create_user")
     */
    public function createUserAction(Request $request)
    {
        
        $form = $this->createForm(UserType::class, new User(), array(
            'action' => $this->generateUrl('admin_create_user'),
            'admin'=>true,
            'validation_groups' => array('Administration')
        ));
        
        $form->add('Create', SubmitType::class, array('label' => 'forms.create','attr' => array('class'=>'btn btn-lg btn-danger btn-block')));
        
        $form->handleRequest($request);
        
        if($form->isValid() and $form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            
            $user = $form->getData();
                                 
            $plainPassword = uniqid();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            
            $user->setActivationhash();
            
            $em->persist($user->getPerson());                        
            $em->persist($user);
            $user->checkNeedsAction();
            $em->flush();
            
            /*$this->sendActivationEMail($user,$plainPassword);//send activartion e-mail
            
            $this->addFlash(
                'notice', $this->get("translator")->trans("mail.notice") .$user->getEmail(). $this->get("translator")->trans("mail.notice2") 
                         
            );*/

            return $this->redirectToRoute("app_admin_users_index");
        }
        

        return $this->render(
            'AppBundle:Admin:userNew.html.twig',
            array('form' => $form->createView())
        );
    }
    
    /**
     * Send activation e-mail
     * 
     * @param \User $user
     * @param String $plainPassword
     */
    private function sendActivationEMail($user,$plainPassword)
    {
        $email=$user->getEmail();
        
        $mailer = $this->get('mailer');

        $message = $mailer->createMessage()
                ->setSubject('Dobrodošli na internetsku platformu EDUCA@SSSH')
                ->setFrom('educa@sssh.hr')
                ->setTo($email)
                ->setBody(
                $this->renderView(
                        'AppBundle:Email:activation.html.twig', 
                        ['user' => $user,'plainPassword'=>$plainPassword]), 
                        'text/html'
                    );

        $mailer->send($message);
    } 
    

    
    /**
     * @Route("/block/{userId}", name="admin_block_user")
     * 
     * @param int $userId
     */
    public function changeActivationsStatus($userId)
    {
        $em    = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($userId);
        
        if (!$user)
        {
          $this->addFlash(
                'error',
                'Unable to find User entity.'           
                );
          return $this->redirectToRoute("error");
        }
        
        if($user->getIsActive())
        {
            $user->setIsActive(0);
        }
        else
        {
            $user->setIsActive(1);
            
            $plainPassword = uniqid();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($encoded);
            
            $user->setActivationhash();
            
            $this->sendActivationEMail($user,$plainPassword);//send activation e-mail
            
        }
        
        $user->getPerson()->setReg(FALSE);
        $user->getPerson()->setNeedsAction();
        
        $em->flush();
        
        return $this->redirectToRoute("app_admin_users_index");
    }
    
    
    /**
     * @Route("/edituser/{id}", name="admin_edit_user")
     * 
     * @param int $id
     */
    public function editUserAction(Request $request, $id)
    {
        
        $em    = $this->getDoctrine()->getManager();
        
        $user=$em->getRepository('AppBundle:User')->find($id);

        if (!$user)
        {
          $this->addFlash(
                'error',
                'Unable to find User entity.'           
                );
          return $this->redirectToRoute("error");
        }
        
        $originalDuties = new ArrayCollection();
        $originalCourses = new ArrayCollection();
        $originalParticipation = new ArrayCollection();
        $originalPrograms = new ArrayCollection();
        
        // Create an ArrayCollection of the current PersonOnDuty objects in the database
        foreach ($user->getPerson()->getDuties() as $duty) {
            $originalDuties->add($duty);
        }

        // Create an ArrayCollection of the current Teacher2course objects in the database
        foreach ($user->getMyCourses() as $course) {
            $originalCourses->add($course);
        }
        
        // Create an ArrayCollection of the current User2course objects in the database
        foreach ($user->getParticipations()as $part) {
            $originalParticipation->add($part);
        }
        
        // Create an ArrayCollection of the current User2program objects in the database
        foreach ($user->getPrograms()as $prog) {
            $originalPrograms->add($prog);
        }
        
        
        $form = $this->createForm(UserType::class, $user, array(
            'action' => $this->generateUrl('admin_edit_user',array('id'=>$id)),
            'admin'=>true
        ));
        
        $form->add('Save', SubmitType::class , array('attr' => array('class'=>'btn btn-lg btn-danger btn-block')));
        
        $form->handleRequest($request);
        
        if($form->isValid() and $form->isSubmitted())
        {
            $user->setUpdated(new \DateTime("Now"));
            
            // remove the relationship between the Person and the PersonOnDuty
            foreach ($originalDuties as $duty) {
                if (false === $user->getPerson()->getDuties()->contains($duty)) {
                    // remove the PersonOnDuty from the Person
                    $user->getPerson()->removeDuty($duty);
                    //remove duty from DB
                    $em->remove($duty);          
                }
            }
            
            // remove the relationship between the User and the Teacher2course
            foreach ($originalCourses as $course) {
                if (false === $user->getMyCourses()->contains($course)) {
                    // remove the User2course from the User
                    $user->removeMyCourse($course);
                    //remove User2course from DB
                    $em->remove($course);          
                }
            }
            
            // remove the relationship between the User and the User2course
            foreach ($originalParticipation as $part) {
                if (false === $user->getParticipations()->contains($part)) {
                    // remove the User2course from the User
                    $user->removeParticipation($part);
                    //remove User2course from DB
                    $em->remove($part);          
                }
            }
            
            // remove the relationship between the User and the User2course
            foreach ($originalPrograms as $program) {
                if (false === $user->getPrograms()->contains($program)) {
                    // remove the User2course from the User
                    $user->removeProgram($program);
                    //remove User2course from DB
                    $em->remove($program);          
                }
            }
            $user->checkNeedsAction();
            $em->persist($user);
            $em->flush();
            
            return $this->redirectToRoute("app_admin_users_index");
        }
        
         return $this->render(
            'AppBundle:Admin:userEdit.html.twig',
            array('form' => $form->createView())
        );
    }
    
    
    /**
     * 
     * @param int $id
     * @return boolean
     * 
     */
    public function deleteUser($id)
    {
        
        $em    = $this->getDoctrine()->getManager();
        $user=$em->getRepository('AppBundle:User')->find($id);

        if (!$user)
        {
          $this->addFlash(
                'error',
                'Unable to find User entity.'           
                );
          return $this->redirectToRoute("error");
        }
      
        try
        {
            
            $em->remove($user);
            $em->flush();
            
            return true;
        }
        catch (\Exception $e)
        {
            error_log($e->getMessage());
            return false;
        }    
         
    }
    
    /**
     * @Route("/deleteusers/", name="admin_delete_users")
     *  
     */
    public function deleteUsersAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        
        $data = $request->request->all();
        
        if(isset($data["checkedRows"]))
        {
            $checkedRows=explode(",",$data["checkedRows"]);
        
            foreach ($checkedRows as $login) {
                $user = $em->getRepository("AppBundle:User")->findOneBy(array('login' => $login));
                $this->deleteUser($user->getId());
            }
        }
        
        return $this->redirectToRoute("app_admin_users_index");
    }
    
    
     /**
     * @Route("/attachusersou/", name="admin_attach_users_ou")
     *  
     */
    public function attachUsersOUAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        
        $data = $request->request->all();
        
        if(!isset($data["checkedRows"]))
        {
            $this->addFlash(
                'error',
                'No users to attach.'           
                );
          return $this->redirectToRoute("error");
        }
        
        if(!isset($data["ou"]))
        {
            $this->addFlash(
                'error',
                'No OU to attach.'           
                );
           return $this->redirectToRoute("error");
        }
            
   
        $checkedRows=explode(",",$data["checkedRows"]);
        
        $ouId=$data["ou"];
        
        foreach ($checkedRows as $login) {
            $user = $em->getRepository("AppBundle:User")->findOneBy(array('login' => $login));
            $ou = $em->getRepository("AppBundle:OU")->find($ouId);
            $user->setOu($ou);
            $em->flush();
        }
        
        return $this->redirectToRoute("app_admin_users_index");
    }
    
     /**
     * @Route("/attachuserssyndicate/", name="admin_attach_users_syndicate")
     *  
     */
    public function attach2SyndicateAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        
        $data = $request->request->all();
        
        if(!isset($data["checkedRows"]))
        {
            $this->addFlash(
                'error',
                'No users to attach.'           
                );
           return $this->redirectToRoute("error");
        }
        
        if(!isset($data["syndicate"]))
        {
            $this->addFlash(
                'error',
                'No syndicate to attach.'           
                );
           return $this->redirectToRoute("error");
        }
            
   
        $checkedRows=explode(",",$data["checkedRows"]);
        
        $id=$data["syndicate"];
        
        foreach ($checkedRows as $login) {
            $user = $em->getRepository("AppBundle:User")->findOneBy(array('login' => $login));
            $syndicate = $em->getRepository("AppBundle:Syndicate")->find($id);
            $user->getPerson()->setSyndicate($syndicate);
            $em->flush();
        }
        
        return $this->redirectToRoute("app_admin_users_index");
    }
    
    /**
     * inverse translate gender
     * 
     * not good enough, but works
     * 
     * @param string $translation
     * @return string
     */
    function getGenderDb($translation)
    {
        switch($translation)
        {
            case "Muški": case "Male": case "Männlich":
                return "male";
            case "Ženski": case "Female": case "Webliches":
                return "female";
            default:
                return $translation;
        }
    }
       
    
     /**
     * Function checks if login is taken and then adds 1 or 2 or 3... to it to become unique
     * 
     * @param string $login
     * @param integer $increment
     * @return string
     */
    public function checkLogin($login,$increment=1)
    {
        $user = $this->getDoctrine()
             ->getRepository('AppBundle:User')
             ->findOneBy(array('login' => $login));
        
        if($user)
        {
            $user = $this->getDoctrine()
             ->getRepository('AppBundle:User')
             ->findOneBy(array('login' => $login.$increment));
            if($user){
                return $this->checkLogin($login,$increment+1);
            }
            else{
                return $login.$increment;
            }
            
        }
        else
        {
            return $login;
        }
    }
    
    
    /**
     * @Route("/widget/", name="admin_widget")
     *  
     */
    public function widgetAction(){
        
        $em = $this->getDoctrine()->getManager();        

        $qb3 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\User', 'a')
            ->join('AppBundle\Entity\Person', 'b')
                ->where('b.id = a.person');
        $qb3->andWhere('b.reg = :reg')
            ->setParameter('reg', 1);

        $reg = $qb3->getQuery()->getSingleScalarResult();
        
        $qb4 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\User2course', 'a')
                ->where('a.status = 5');

        $app = $qb4->getQuery()->getSingleScalarResult();
        
        $qb5 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\User2course', 'a')
            ->join('AppBundle\Entity\OpenCourse', 'b')
                ->where('b.id = a.openCourse');
        $qb5->andWhere('b.registrationFee = :registrationFee')
            ->setParameter('registrationFee', 1);
        $qb5->andWhere('a.registrationFee = :fee')
            ->setParameter('fee', 0);
        $qb5->andWhere('a.status in (3,4)');

        $fee = $qb5->getQuery()->getSingleScalarResult();
        
        $qb6 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\User2course', 'a')
            ->join('AppBundle\Entity\OpenCourse', 'b')
                ->where('b.id = a.openCourse');
        $qb6->andWhere('a.sertificate = :cert')
            ->setParameter('cert', 0);
        $qb6->andWhere('a.status = 3');

        $cert = $qb6->getQuery()->getSingleScalarResult();
        
         $qb7 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\User2program', 'a')
                ->where('a.finished=1');
        $qb7->andWhere('a.diploma=0');

        $dipl = $qb7->getQuery()->getSingleScalarResult();
        
        $qb8 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\OpenCourse', 'a')
                ->where('a.confirmed = :confirmed')
            ->setParameter('confirmed', FALSE);

        $plannedCount = $qb8->getQuery()->getSingleScalarResult();

        $qb9 = $em->createQueryBuilder()
            ->select('COUNT(a)')
            ->from('AppBundle\Entity\OpenCourse', 'a')
                ->where('a.evoluation is null');

        $evaluationCount = $qb9->getQuery()->getSingleScalarResult();
        
        return $this->render(
            'AppBundle:Admin:_widget.html.twig',
                array('reg'=>$reg,'app'=>$app,'fee'=>$fee,'cert'=>$cert,'dipl'=>$dipl,'plannedCount'=>$plannedCount,'evaluationCount'=>$evaluationCount)
        );
    }
    
    
   /**
     * Admin uesers on open course
     *
     * @Route("/user2course", name="app_admin_user2course_index")
     * 
     */
    public function user2courseIndexAction() {
        
        $em    = $this->getDoctrine()->getManager();
        $syndicates = $em->getRepository('AppBundle:Syndicate')->findAll();
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        $courses = $em->getRepository('AppBundle:Course')->findAll();
        $duties = $em->getRepository('AppBundle:Duty')->findAll();
        $user2courseStatuses = StatusConstants::USER2COURSE_STATUSES;
        $openCoourseStatuses = StatusConstants::COURSE_STATUSES;
        
        return $this->render('AppBundle:Admin:user2courseIndex.html.twig',array(
            "syndicates"=>$syndicates, 
            'programs'=>$programs, 
            'courses'=>$courses, 
            'duties'=>$duties, 
            'user2courseStatuses'=> $user2courseStatuses,
            'openCoourseStatuses'=> $openCoourseStatuses,
            ));
        
    }
    
    
    /** Ajax from Controller
    *   ?limit=10&offset=0&order=asc&sort=date
    * @Route("/ajaxuser2course/", name="get_user2course_ajax_call")
    *
    */
   public function user2courseIndexJsonAction(Request $request) {

        if (! $request->isXmlHttpRequest()) {
            $this->addFlash('error', 'This page is not made for browsers.');
            return $this->redirectToRoute("error");
        }


        $limit=$request->query->get('limit', '10');
        $offset=$request->query->get('offset', '0');
        $order=$request->query->get('order', 'ASC');
        $sort=$request->query->get('sort', 'id');
        
        

        //$search=$request->query->get('search', '');
        //we dont use serach box for now

        $filter=json_decode($request->query->get('filter', ''),TRUE);
        
                
        $em=$this->getDoctrine()->getManager();

        //for one page of results
        $qb = $em->createQueryBuilder();
        $qb->select( "a" )
            ->from('AppBundle\Entity\User2course', 'a')
                ->join('a.user', 'b')
                ->join('b.person', 'd')
                ->join('a.openCourse', 'c')
                ->join('c.course', 'e')
                ->join('c.address', 'f');
        $qb ->where($qb->expr()->gt('a.id',1));//not good
        
        //for total number of rows
        $qb2 = $em->createQueryBuilder();
        $qb2->select( "count(a.id)" )
            ->from('AppBundle\Entity\User2course', 'a')
            ->join('a.user', 'b')
            ->join('b.person', 'd')
            ->join('a.openCourse', 'c')
            ->join('c.course', 'e')
            ->join('c.address', 'f');
        $qb2 ->where($qb2->expr()->gt('a.id',1));//not good
        
       if (isset($filter["program"])) {
           
            $qb->andWhere($qb->expr()->eq('e.program',$filter["program"]));
            $qb2->andWhere($qb2->expr()->eq('e.program',$filter["program"]));
        }//Adding another, optional condition
        
        if (isset($filter["course"])) {
            $courses=explode(",",$filter["course"]);
            $qb->andWhere($qb->expr()->in('c.course',$courses));
            $qb2->andWhere($qb2->expr()->in('c.course',$courses));
        }//Adding another, optional condition
        
        if (isset($filter["start"])) {
            $qb->andWhere($qb->expr()->gte('c.start',':start0'))
        ->setParameter('start0',new \DateTime($filter["start"]));
            $qb2->andWhere($qb2->expr()->gte('c.start',':start0'))
        ->setParameter('start0',new \DateTime($filter["start"]));
        }//Adding another, optional condition
        
        if (isset($filter["end"])) {
            $end=new \DateTime($filter["end"]);
            $end->setTime(23, 59, 59);
            $qb->andWhere($qb->expr()->lte('c.end',':end0'))
        ->setParameter('end0',$end);
            $qb2->andWhere($qb2->expr()->lte('c.end',':end0'))
        ->setParameter('end0',$end);
        }//Adding another, optional condition
        
        if (isset($filter["city"])) {
            $qb->andWhere($qb->expr()->like('f.city',':city0'))
                ->setParameter('city0','%'.$filter["city"].'%');
            $qb2->andWhere($qb2->expr()->like('f.city',':city0'))
                ->setParameter('city0','%'.$filter["city"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["email"])) {
           
            $qb->andWhere($qb->expr()->like('b.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
            $qb2->andWhere($qb2->expr()->like('b.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
        }//Adding another, optional condition
        
       if (isset($filter["telephone"])) {
           
            $qb->andWhere($qb->expr()->like('d.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
            $qb2->andWhere($qb2->expr()->like('d.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["firstname"])) {
           
            $qb->andWhere($qb->expr()->like('d.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
            $qb2->andWhere($qb2->expr()->like('d.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
        }//Adding another, optional condition
        
        
        if (isset($filter["lastname"])) {
           
            $qb->andWhere($qb->expr()->like('d.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
            $qb2->andWhere($qb2->expr()->like('d.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["gender"])) {
            
            $filter["gender"]=$this->getGenderDb($filter["gender"]);
           
            $qb->andWhere($qb->expr()->eq('d.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
            $qb2->andWhere($qb2->expr()->eq('d.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
        }//Adding another, optional condition
        
        
        
        if (isset($filter["syndicate"])) {
           
            $qb->andWhere($qb->expr()->eq('d.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
            $qb2->andWhere($qb2->expr()->eq('d.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
        }//Adding another, optional condition
        
        if (isset($filter["employer"])) {
           
            $qb->andWhere($qb->expr()->like('d.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
            $qb2->andWhere($qb2->expr()->like('d.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["duties"])) {
           $personsOnDuty=$em->getRepository("AppBundle:PersonOnDuty")->findBy(array('duty'=>$filter["duties"]));
           $duties=array();
           foreach($personsOnDuty as $duty){
               $duties[]=$duty->getPerson()->getId();
           }
            $qb->andWhere($qb->expr()->in('d.id',$duties));
            $qb2->andWhere($qb2->expr()->in('d.id',$duties));
        }//Adding another, optional condition
        
        if (isset($filter["category"])) {
            $qb->andWhere($qb->expr()->like('c.category',':category0'))
                ->setParameter('category0','%'.$filter["category"].'%');
            $qb2->andWhere($qb2->expr()->like('c.category',':category0'))
                ->setParameter('category0','%'.$filter["category"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["status_user2course"])) {
           
            $qb->andWhere($qb->expr()->eq('a.status',':status_user2course'))
                ->setParameter('status_user2course',$filter["status_user2course"]);
            $qb2->andWhere($qb2->expr()->eq('a.status',':status_user2course'))
                ->setParameter('status_user2course',$filter["status_user2course"]);
        }//Adding another, optional condition
        
        
        if (isset($filter["status_open_course"])) {
           
            $qb->andWhere($qb->expr()->eq('c.confirmed',':status_open_course'))
                ->setParameter('status_open_course',$filter["status_open_course"]);
            $qb2->andWhere($qb2->expr()->eq('c.confirmed',':status_open_course'))
                ->setParameter('status_open_course',$filter["status_open_course"]);
        }//Adding another, optional condition
        
        switch($sort)
        {
            case "firstname": 
            case "lastname": 
            case "telephone": 
            case "gender": 
            case "syndicate": 
            case "needsAction":
            case "employer":
            case "birthdate":
            case "sendSerificateTo":
                $sort="d.$sort";
                break;
            case "course":
            case "start":
            case "end":
            case "category":
                $sort="c.$sort";
                break;
            case "program":
                $sort="e.$sort";
                break;
            case "city":
                $sort="f.$sort";
                break;
            case "status_user2course":
                $sort = "a.status";
                break;
            case "status_open_course":
                $sort = "c.confirmed";
                break;
            
            default:
                $sort="b.$sort";
                break;
        }
        
        $qb->orderBy($sort, $order)
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        
        $query = $qb->getQuery();
        $users2couses = $query->getResult();
        
        $query2 = $qb2->getQuery();
        $count = $query2->getResult();
        
        $json=array();
       
        $json["total"]=$count[0][1];
    
        $data=array();
       
        $i=0;
        $user2courseStatuses = StatusConstants::USER2COURSE_STATUSES;
        $openCoourseStatuses = StatusConstants::COURSE_STATUSES;
        
        foreach($users2couses as $user2course)
        {
            
            $data[$i]["course"] = $user2course->getOpenCourse()->getCourse()->getName();
            $data[$i]["program"] = $user2course->getOpenCourse()->getCourse()->getProgram()->getName();
            $data[$i]["start"] = $user2course->getOpenCourse()->getStart()->format('d.m.Y');
            $data[$i]["end"] = $user2course->getOpenCourse()->getEnd()->format('d.m.Y');
            $data[$i]["city"] = $user2course->getOpenCourse()->getAddress()->getCity();
            $data[$i]["status_open_course"] = $openCoourseStatuses[$user2course->getOpenCourse()->getConfirmed()];
            $data[$i]["category"] = $user2course->getOpenCourse()->getCategory();
            
            $data[$i]["status_user2course"] = $user2courseStatuses[$user2course->getStatus()];
            
            $data[$i]["login"]=$user2course->getUser()->getLogin();
            $data[$i]["email"]=$user2course->getUser()->getEmail();
            $data[$i]["telephone"]=$user2course->getUser()->getPerson()->getTelephone();
           
            $data[$i]["role"]=$user2course->getUser()->getRole()->getName();//role is always set
           
            $data[$i]["firstname"]=$user2course->getUser()->getPerson()->getFirstname();
            $data[$i]["lastname"]=$user2course->getUser()->getPerson()->getLastname();
            $data[$i]["birthdate"]=$user2course->getUser()->getPerson()->getBirthday()->format("d.m.Y");
            $data[$i]["duties"]="<ul>";
            foreach($user2course->getUser()->getPerson()->getDuties() as $duty){
                $data[$i]["duties"].="<li>".$duty->getDuty()->getName()."</li>";
            }
            $data[$i]["duties"].="</ul>";
           
            $data[$i]["gender"]=$this->get('translator')->trans($user2course->getUser()->getPerson()->getGender());
           
            $data[$i]["syndicate"]=$user2course->getUser()->getPerson()->getSyndicate()->getName();
            $data[$i]["employer"]=$user2course->getUser()->getPerson()->getEmployer();
           
            $data[$i]["isActive"]="<a href='"
                   .$this->generateUrl('admin_block_user', 
                           array('userId'=>$user2course->getUser()->getId() ))
                   ."'><img src='/bundles/app/images/icons/".($user2course->getUser()->getIsActive()?"unlocked":"locked").".png"
                    ."' alt=\"".
                   $this->get('translator')->trans('lock.unlock')
                   ."\" title=\"".
                        $this->get('translator')->trans('lock.unlock')
                        ."\" class=\"action-icons\"/>"
                    ."</a> ";
           
           $data[$i]["reg"]=$user2course->getUser()->getPerson()->getReg()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["app"]=$user2course->getUser()->getPerson()->getApp()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["fee"]=$user2course->getUser()->getPerson()->getFee()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["cert"]=$user2course->getUser()->getPerson()->getCert()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["dipl"]=$user2course->getUser()->getPerson()->getDipl()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           $data[$i]["needsAction"]=$user2course->getUser()->getPerson()->getNeedsAction()?"<img src='/bundles/app/images/icons/kocka.png"
                    ."' alt=\"x\" title=\"x\" class=\"action-icons\"/>":"";
           
           
           $data[$i]["actions"]="";
           
           $data[$i]["sendSerificateTo"]=$user2course->getUser()->getPerson()->getSendSerificateTo()==1?"Osobna adresa":"Službena adresa";
           
           $data[$i][" "]=$user2course->getUser()->getPerson()->getOfficialAddress()?$user2course->getUser()->getPerson()->getOfficialAddress()->getStreet():"-";
           $data[$i]["officialAddressCity"]=$user2course->getUser()->getPerson()->getOfficialAddress()?$user2course->getUser()->getPerson()->getOfficialAddress()->getCity():"-";
           $data[$i]["officialAddressZip"]=$user2course->getUser()->getPerson()->getOfficialAddress()?$user2course->getUser()->getPerson()->getOfficialAddress()->getZip():"-";

           $data[$i]["privatAddressStreet"]=$user2course->getUser()->getPerson()->getPrivatAddress()?$user2course->getUser()->getPerson()->getPrivatAddress()->getStreet():"-";
           $data[$i]["privatAddressCity"]=$user2course->getUser()->getPerson()->getPrivatAddress()?$user2course->getUser()->getPerson()->getPrivatAddress()->getCity():"-";
           $data[$i]["privatAddressZip"]=$user2course->getUser()->getPerson()->getPrivatAddress()?$user2course->getUser()->getPerson()->getPrivatAddress()->getZip():"-";
 
           $i++;
        }
        
        
        $json["rows"]=$data;
       
        return new JsonResponse($json);
    } 
    
    /**
     * Admin teachers on open course
     *
     * @Route("/teacher2opencourse", name="app_admin_teacher2opencourse_index")
     * 
     */
    public function teacher2opencourseIndexAction() {
        
        $em    = $this->getDoctrine()->getManager();
        $syndicates = $em->getRepository('AppBundle:Syndicate')->findAll();
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        $courses = $em->getRepository('AppBundle:Course')->findAll();
        $openCoourseStatuses = StatusConstants::COURSE_STATUSES;
        return $this->render('AppBundle:Admin:teacher2openCourseIndex.html.twig',array(
            "syndicates"=>$syndicates, 
            'programs'=>$programs, 
            'courses'=>$courses,
            'openCoourseStatuses'=> $openCoourseStatuses,
            ));
        
    }
    
    
    /** Ajax from Controller
    *   ?limit=10&offset=0&order=asc&sort=date
    * @Route("/ajaxteacher2opencourse/", name="get_teacher2opencourse_ajax_call")
    *
    */
   public function teacher2opencourseIndexJsonAction(Request $request) {

        if (! $request->isXmlHttpRequest()) {
            $this->addFlash(
                'error',
                'This page is not made for browsers.'           
                );
          return $this->redirectToRoute("error");
        }


        $limit=$request->query->get('limit', '10');
        $offset=$request->query->get('offset', '0');
        $order=$request->query->get('order', 'ASC');
        $sort=$request->query->get('sort', 'id');
        
        

        //$search=$request->query->get('search', '');
        //we dont use serach box for now

        $filter=json_decode($request->query->get('filter', ''),TRUE);
        
                
        $em=$this->getDoctrine()->getManager();

        //for one page of results
        $qb = $em->createQueryBuilder();
        $qb->select( "a" )
            ->from('AppBundle\Entity\Teacher2openCourse', 'a')
                ->join('a.idUser', 'b')
                ->join('b.person', 'd')
                ->join('a.idOpenCourse', 'c')
                ->join('c.course', 'e')
                ->join('c.address', 'f');
        $qb ->where($qb->expr()->gt('a.id',1));//not good
        
        //for total number of rows
        $qb2 = $em->createQueryBuilder();
        $qb2->select( "count(a.id)" )
            ->from('AppBundle\Entity\Teacher2openCourse', 'a')
            ->join('a.idUser', 'b')
            ->join('b.person', 'd')
            ->join('a.idOpenCourse', 'c')
            ->join('c.course', 'e')
            ->join('c.address', 'f');
        $qb2 ->where($qb2->expr()->gt('a.id',1));//not good
        
       if (isset($filter["program"])) {
           
            $qb->andWhere($qb->expr()->eq('e.program',$filter["program"]));
            $qb2->andWhere($qb2->expr()->eq('e.program',$filter["program"]));
        }//Adding another, optional condition
        
        if (isset($filter["course"])) {
            $courses=explode(",",$filter["course"]);
            $qb->andWhere($qb->expr()->in('c.course',$courses));
            $qb2->andWhere($qb2->expr()->in('c.course',$courses));
        }//Adding another, optional condition
        
        if (isset($filter["start"])) {
            $qb->andWhere($qb->expr()->gte('c.start',':start0'))
        ->setParameter('start0',new \DateTime($filter["start"]));
            $qb2->andWhere($qb2->expr()->gte('c.start',':start0'))
        ->setParameter('start0',new \DateTime($filter["start"]));
        }//Adding another, optional condition
        
        if (isset($filter["end"])) {
            $end=new \DateTime($filter["end"]);
            $end->setTime(23, 59, 59);
            $qb->andWhere($qb->expr()->lte('c.end',':end0'))
        ->setParameter('end0',$end);
            $qb2->andWhere($qb2->expr()->lte('c.end',':end0'))
        ->setParameter('end0',$end);
        }//Adding another, optional condition
        
        if (isset($filter["city"])) {
            $qb->andWhere($qb->expr()->like('f.city',':city0'))
                ->setParameter('city0','%'.$filter["city"].'%');
            $qb2->andWhere($qb2->expr()->like('f.city',':city0'))
                ->setParameter('city0','%'.$filter["city"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["email"])) {
           
            $qb->andWhere($qb->expr()->like('b.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
            $qb2->andWhere($qb2->expr()->like('b.email',':email'))
                ->setParameter('email','%'.$filter["email"].'%');
        }//Adding another, optional condition
        
       if (isset($filter["telephone"])) {
           
            $qb->andWhere($qb->expr()->like('d.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
            $qb2->andWhere($qb2->expr()->like('d.telephone',':telephone'))
                ->setParameter('telephone','%'.$filter["telephone"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["firstname"])) {
           
            $qb->andWhere($qb->expr()->like('d.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
            $qb2->andWhere($qb2->expr()->like('d.firstname',':firstname'))
                ->setParameter('firstname','%'.$filter["firstname"].'%');
        }//Adding another, optional condition
        
        
        if (isset($filter["lastname"])) {
           
            $qb->andWhere($qb->expr()->like('d.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
            $qb2->andWhere($qb2->expr()->like('d.lastname',':lastname'))
                ->setParameter('lastname','%'.$filter["lastname"].'%');
        }//Adding another, optional condition
        
        if (isset($filter["gender"])) {
            
            $filter["gender"]=$this->getGenderDb($filter["gender"]);
           
            $qb->andWhere($qb->expr()->eq('d.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
            $qb2->andWhere($qb2->expr()->eq('d.gender',':gender'))
                ->setParameter('gender',$filter["gender"]);
        }//Adding another, optional condition
        
        
        
        if (isset($filter["syndicate"])) {
           
            $qb->andWhere($qb->expr()->eq('d.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
            $qb2->andWhere($qb2->expr()->eq('d.syndicate',':syndicate'))
                ->setParameter('syndicate',$filter["syndicate"]);
        }//Adding another, optional condition
        
        if (isset($filter["employer"])) {
           
            $qb->andWhere($qb->expr()->like('d.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
            $qb2->andWhere($qb2->expr()->like('d.employer',':employer'))
                ->setParameter('employer','%'.$filter["employer"].'%');
        }//Adding another, optional condition
        
        
        if (isset($filter["status_open_course"])) {
           
            $qb->andWhere($qb->expr()->eq('c.confirmed',':status_open_course'))
                ->setParameter('status_open_course',$filter["status_open_course"]);
            $qb2->andWhere($qb2->expr()->eq('c.confirmed',':status_open_course'))
                ->setParameter('status_open_course',$filter["status_open_course"]);
        }//Adding another, optional condition
        
        switch($sort)
        {
            case "firstname": 
            case "lastname": 
            case "telephone": 
            case "gender": 
            case "syndicate": 
            case "needsAction":
            case "employer":
            case "birthdate":
            case "sendSerificateTo":
                $sort="d.$sort";
                break;
            case "course":
            case "start":
            case "end":
                $sort="c.$sort";
                break;
            case "program":
                $sort="e.$sort";
                break;
            case "city":
                $sort="f.$sort";
                break;
            case "status_open_course":
                $sort = "c.confirmed";
                break;
            
            default:
                $sort="b.$sort";
                break;
        }
        
        $qb->orderBy($sort, $order)
            ->setFirstResult( $offset )
            ->setMaxResults( $limit );
        
        $query = $qb->getQuery();
        $users2couses = $query->getResult();
        
        $query2 = $qb2->getQuery();
        $count = $query2->getResult();
        
        $json=array();
       
        $json["total"]=$count[0][1];
    
        $data=array();
       
        $openCoourseStatuses = StatusConstants::COURSE_STATUSES;
        $i=0;
        foreach($users2couses as $user2course)
        {
            
            $data[$i]["course"]=$user2course->getIdOpenCourse()->getCourse()->getName();
            $data[$i]["program"]=$user2course->getIdOpenCourse()->getCourse()->getProgram()->getName();
            $data[$i]["start"]=$user2course->getIdOpenCourse()->getStart()->format('d.m.Y');
            $data[$i]["end"]=$user2course->getIdOpenCourse()->getEnd()->format('d.m.Y');
            $data[$i]["city"]=$user2course->getIdOpenCourse()->getAddress()->getCity();
            $data[$i]["status_open_course"] = $openCoourseStatuses[$user2course->getIdOpenCourse()->getConfirmed()];

            $data[$i]["login"]=$user2course->getIdUser()->getLogin();
            $data[$i]["email"]=$user2course->getIdUser()->getEmail();
            $data[$i]["telephone"]=$user2course->getIdUser()->getPerson()->getTelephone();
           
           
            $data[$i]["firstname"]=$user2course->getIdUser()->getPerson()->getFirstname();
            $data[$i]["lastname"]=$user2course->getIdUser()->getPerson()->getLastname();
            $data[$i]["birthdate"]=$user2course->getIdUser()->getPerson()->getBirthday()->format("d.m.Y");
           
            $data[$i]["gender"]=$this->get('translator')->trans($user2course->getIdUser()->getPerson()->getGender());
           
            $data[$i]["syndicate"]=$user2course->getIdUser()->getPerson()->getSyndicate()->getName();
            $data[$i]["employer"]=$user2course->getIdUser()->getPerson()->getEmployer();
           
            
           
           
           
           $data[$i]["actions"]="";
           
           $data[$i]["sendSerificateTo"]=$user2course->getIdUser()->getPerson()->getSendSerificateTo()==1?"Osobna adresa":"Službena adresa";
           
           $data[$i]["officialAddressStreet"]=$user2course->getIdUser()->getPerson()->getOfficialAddress()?$user2course->getIdUser()->getPerson()->getOfficialAddress()->getStreet():"-";
           $data[$i]["officialAddressCity"]=$user2course->getIdUser()->getPerson()->getOfficialAddress()?$user2course->getIdUser()->getPerson()->getOfficialAddress()->getCity():"-";
           $data[$i]["officialAddressZip"]=$user2course->getIdUser()->getPerson()->getOfficialAddress()?$user2course->getIdUser()->getPerson()->getOfficialAddress()->getZip():"-";

           $data[$i]["privatAddressStreet"]=$user2course->getIdUser()->getPerson()->getPrivatAddress()?$user2course->getIdUser()->getPerson()->getPrivatAddress()->getStreet():"-";
           $data[$i]["privatAddressCity"]=$user2course->getIdUser()->getPerson()->getPrivatAddress()?$user2course->getIdUser()->getPerson()->getPrivatAddress()->getCity():"-";
           $data[$i]["privatAddressZip"]=$user2course->getIdUser()->getPerson()->getPrivatAddress()?$user2course->getIdUser()->getPerson()->getPrivatAddress()->getZip():"-";
 
           $i++;
        }
        
        
        $json["rows"]=$data;
       
        return new JsonResponse($json);
    } 

}

