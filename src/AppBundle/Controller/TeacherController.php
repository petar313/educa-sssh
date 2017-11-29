<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
/**
 * Teacher controller.
 *
 * @Route("/teacher")
 */
class TeacherController extends Controller
{
    /**
     * Page that shows teacher courses
     * 
     * @Route("/mycourses", name="my_courses_teacher")
     */
    public function myCoursesAction(){
        $em = $this->getDoctrine()->getManager();
        $courses=$this->getUser()->getMyCourses();
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        
        return $this->render(
            'AppBundle:Teacher:myCourses.html.twig',
                array('courses'=>$courses,
                    'programs'=>$programs)
        );  
    }
    
    /**
     * Home page for teachers
     * 
     * @Route("/myopencourses", name="my_open_courses_teacher")
     */
    public function myOpenCoursesAction(){
        $em = $this->getDoctrine()->getManager();
        $courses=$this->getUser()->getMyOpenCourses();
        $programs = $em->getRepository('AppBundle:Program')->findAll();
        return $this->render(
            'AppBundle:Teacher:myOpenCourses.html.twig',
                array('courses'=>$courses,
                    'programs'=>$programs)
        );  
    }
}
