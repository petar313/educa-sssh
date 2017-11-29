<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;



class ResetPasswordType extends AbstractType
{
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login', TextType::class, array( 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'username', 'class'=>'input_row form-control naslov'),));
        $builder->add('email', EmailType::class, array( 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'email', 'class'=>'form-control naslov'),));
        $builder->setAction("resetpwd")
            ->setMethod("POST");
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('ResetPassword'),
        ));
    }
    

    public function getName()
    {
        return 'reset_password_from';
    }
}

