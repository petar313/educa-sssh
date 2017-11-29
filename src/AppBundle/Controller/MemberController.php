<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
/**
 * Member controller.
 *
 * @Route("/member")
 */
class MemberController extends Controller
{
    /**
     * Homepage for all members
     * 
     * @Route("/", name="homepage_member")
     */
    public function indexAction(Request $request)
    {
        if(null !== $request->query->get('showMessage')){
           $showMessage=$request->query->get('showMessage'); 
        }
        else{
            $showMessage=FALSE;
        }
        
        
        $em = $this->getDoctrine()->getManager();
        
        $referer=$this->redirect($request->server->get('HTTP_REFERER'));
        
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
                array('planed'=>$planned,'confirmed'=>$confirmed,'programs'=>$programs, 'showMessage'=>$showMessage)
        );
    }
}
