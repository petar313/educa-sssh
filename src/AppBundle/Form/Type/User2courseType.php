<?php
// src/AppBundle/Form/Type/user2courseType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class User2courseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('user',  EntityType::class,array(
                'label' => 'user',
                'class' => 'AppBundle:User',
                'required'=>false,
                'label_attr' => array('class'=>'sr-only '),
                'attr' => array('class'=>'form-control')
                ));
        
        $builder->add('status', ChoiceType::class, array(
                'label_attr' => array('class'=>'naslov '),
                'choices'  => array(
                    'Na odobrenju' => 5,
                    'Završen' => 3,
                    'Odobren' => 4, 
                    'Odbijen' => 6,
                    'Nije završio' => 7,
            ),
        ));
        
        $builder->add('registrationFee', ChoiceType::class, array(
                'label_attr' => array('class'=>'naslov '),
                'choices'  => array(
                    'Nije plaćena kotizacija' => FALSE,
                    'Plaćena kotizacija' => TRUE,
            ),
        ));
        
        $builder->add('sertificate', ChoiceType::class, array(
                'label_attr' => array('class'=>'naslov '),
                'choices'  => array(
                    'Nije isporučen certifikat' => FALSE,
                    'Isporučen certifikat' => TRUE,
            ),
        ));
        
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User2course'
        ));
    }

    public function getName()
    {
        return 'user2course';
    }
}