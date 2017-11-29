<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('newPassword', RepeatedType::class , array(
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'required' => true,
            'first_options'  => array('label' => 'password','label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'program.codenew', 'class'=>'form-control')),
            'second_options' => array('label' => 'registration.confirm','label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'program.codenew', 'class'=>'form-control')),
        ));
        $builder->add('Change', SubmitType::class, array('label' => 'change', 'attr' => array('class'=>'btn btn-lg btn-danger btn-block'),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\ChangePassword',
        ));
    }

    public function getName()
    {
        return 'change_passwd';
    }
}

