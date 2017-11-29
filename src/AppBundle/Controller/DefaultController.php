<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;


class DefaultController extends Controller
{
    /**
     * the only public page - all other require login
     * 
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
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
     * redirects admin to admin area, user, 
     * 
     * @Route("/check", name="check_permission")
     */
    public function checkPermissionAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
         */
        
        $user = $this->getUser();
        if(is_null($user)){
            return $this->redirectToRoute("homepage");
        }
        $roleId = $user->getRole()->getId();
        
        if($roleId==2){
            return $this->redirectToRoute("homepage_admin");
        }else{
            return $this->redirectToRoute("homepage_member");
        }
    }
    
    /**
     * redirects admin to admin area, user, 
     * 
     * @Route("/error", name="error")
     */
    public function errorAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
         */
        
        
        return $this->redirectToRoute("homepage");
        
    }
    
     /**
     * Get data for Calendar
     * 
     * @Route("/calendarjson/", name="calendar_json")
     */
    public function calendarJSONAction(Request $request)
    {
       $user=  $this->getUser();
       
        $json["success"]=1;
        $json["result"]=array();

        $em = $this->getDoctrine()->getManager();

        //$terms = $em->getRepository('AppBundle:Term')->findAll();
        $qb = $em->createQueryBuilder();
        $qb->select( "a" )
            ->from('AppBundle\Entity\Term', 'a')
            ->join('AppBundle\Entity\OpenCourse', 'b')
            ->where($qb->expr()->eq('b.id','a.openCourse'))
            ->andWhere($qb->expr()->eq('b.visibility',1))
            ->andWhere($qb->expr()->not($qb->expr()->eq('b.confirmed',3)));//0?
        $query = $qb->getQuery();
        $terms = $query->getResult();
        
        foreach($terms as $i=>$term)
        {
            $json["result"][$i]["id"]=$term->getId();
            $json["result"][$i]["title"]=$term->getOpenCourse()->getNameAndAdrress();
            $json["result"][$i]["url"]=$this->generateUrl('opencourse_show',array('id'=>$term->getOpenCourse()->getId()));
            if($user and in_array($term->getOpenCourse(),$user->getTeachingCourses())){
                $json["result"][$i]["class"]="pink";
            }
            else{
                $json["result"][$i]["class"]="event-warning";
            }
            $json["result"][$i]["start"]=(string)$term->getStart();
            $json["result"][$i]["end"]=(string)$term->getEnd();
        }

        return new JsonResponse($json);
    }
}
