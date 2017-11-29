<?php

// src/AppBundle/Form/Type/RegistrationType.php
namespace AppBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', UserType::class,array( 'label_attr' => array('class' => 'sr-only'),'admin'=>false));
        $builder->add(
            'terms',
            CheckboxType::class,
            array('property_path' => 'termsAccepted', 'label' => 'registration.agree', 'label_attr' => array('class'=>'label2'))
        );
        $builder->add('Register', SubmitType::class, array('label' => 'registration.submit', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block'),));
    }

    public function getName()
    {
        return 'registration';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Registration'),
        ));
    }
}