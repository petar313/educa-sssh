<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
                'codeProgram', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'program.code',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'name', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'program.name',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'target_group', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'program.target_group',
                    'attr' => array( 
                        'class'=>'form-control'),));
        $builder->add(
                'description', 
                TextareaType::class,    
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'program.description', 
                    'attr' => array(
                        'class'=>'form-control'),));
        $builder->add(
                'color', 
                TextType::class, 
                array( 'label_attr' => array('class' => 'naslov'),
                    'label'=>'program.color',
                    'attr' => array( 
                        'class'=>'form-control colorpicker-default form-control'),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array( 
            'validation_groups' => array('program_type'),
            'data_class' => 'AppBundle\Entity\Program'
        ));
    }

    public function getName()
    {
        return 'program_type';
    }
}
