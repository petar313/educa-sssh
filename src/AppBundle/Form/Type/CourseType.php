<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
                'codeCourse', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.code',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'name', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.name', 
                    'attr' => array(
                        'class'=>'form-control'),));
        $builder->add(
                'target_group', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.target_group',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'description', 
                TextareaType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.description',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'content', 
                TextareaType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.content', 
                    'attr' => array(
                        'class'=>'form-control'),));
        $builder->add(
                'outcomes', 
                TextareaType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.outcomes',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'durationHours', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'course.durationHours', 
                    'attr' => array(
                        'class'=>'form-control'),));
  
        
        $builder->add('program', EntityType::class, array(
                'label_attr' => array('class' => 'naslov'),
                'class' => 'AppBundle:Program',
                'label' => 'course.program',
            'attr' => array('class'=>'form-control')));
        
        $builder->add('trainers', CollectionType::class, array(
            'entry_type' => Course2teacherType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' trainer_wrapper naslov '))
        ));
        
        $builder->add('materials', CollectionType::class, array(
            'entry_type' => MaterialType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' material_wrapper naslov material_style '))
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array( 
            'validation_groups' => array('course_type'),
            'data_class' => 'AppBundle\Entity\Course',
        ));
    }

    public function getName()
    {
        return 'course_type';
    }
}
