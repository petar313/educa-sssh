<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Model\InitializableControllerInterface;//ovo ukljucujemo kasnije
use AppBundle\Entity\Material;


/**
 * Admin controller.
 *
 * @Route("/material")
 */
class MaterialController extends Controller //implements InitializableControllerInterface 
{

    
    public function indexAction($id,$trainersMaterials=FALSE){
        $em = $this->getDoctrine()->getManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select( "a" )
            ->from('AppBundle\Entity\Material', 'a') 
            ->where($qb->expr()->eq('a.course',$id))
            ->andWhere($qb->expr()->eq('a.teacherMaterials',$trainersMaterials));
        $query = $qb->getQuery();
        $materials = $query->getResult();
        
        return $this->render(
            'AppBundle:Material:_index.html.twig',
            array('materials' => $materials)
        );
    }
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/show/{material}", name="show_material")
     */
    public function showAction(Material $material){
        $file = $material->getContent();              
        $response = new \Symfony\Component\HttpFoundation\Response(base64_decode(stream_get_contents($file)), 200, array(
                'Content-Type' => $material->getMimetype(),
                
                'Content-Disposition' => 'attachment; filename="'.$material->getName().'.'.$material->getExtension().'"',
        ));

        return $response;
    }
    

}

