<?php
// src/AppBundle/Form/Type/PersonOnDutyType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PersonOnDutyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('duty',  EntityType::class,array(
                'label' => 'pers_info.duty',
                'class' => 'AppBundle:Duty',
                'required'=>false,
                'label_attr' => array('class'=>'sr-only '),
                'attr' => array('class'=>'form-control')
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\PersonOnDuty'
        ));
    }

    public function getName()
    {
        return 'person_on_duty';
    }
}