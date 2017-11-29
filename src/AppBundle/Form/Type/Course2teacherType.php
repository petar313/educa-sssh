<?php
// src/AppBundle/Form/Type/Course2teacherType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class Course2teacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('idUser',  EntityType::class,array(
                'label' => 'treiner',
                'class' => 'AppBundle:User',
                'query_builder' => function (EntityRepository $er) {   
                    $qb=$er->createQueryBuilder('u');
                     $qb->where($qb->expr()->eq('u.role',3));
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
            'data_class' => 'AppBundle\Entity\Teacher2Course'
        ));
    }

    public function getName()
    {
        return 'course2teacher';
    }
}