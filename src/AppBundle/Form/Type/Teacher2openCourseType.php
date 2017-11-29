<?php
// src/AppBundle/Form/Type/Teacher2openCourseType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class Teacher2openCourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('idUser',  EntityType::class,array(
                'label' => 'treiner',
                'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) use ($options) {   
                    $qb=$er->createQueryBuilder('u')->select('u');
                    
                    $qb->join('AppBundle\Entity\Teacher2course','t');
                    $qb->where($qb->expr()->eq('t.idUser',"u.id"));
                     $qb->andWhere($qb->expr()->eq('t.idCourse',$options["course"]));
                      $qb->orderBy('u.login', 'ASC');
                    return $qb;
                },
                'required'=>false,
                'label_attr' => array('class'=>'sr-only '),
                'attr' => array('class'=>'form-control')
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Teacher2openCourse',
            'course'=>NULL
        ));
    }

    public function getName()
    {
        return 'teacher2opencourse';
    }
}