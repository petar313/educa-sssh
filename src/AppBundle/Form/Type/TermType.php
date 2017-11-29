<?php
// src/AppBundle/Form/Type/TermType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class TermType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', DateType::class, array(
                     'widget' => 'choice',
                     'label'=>'Datum',
                     'years' => range(date('Y'), date('Y')-70),
                     'format' => 'dd  M  yyyy',
                     'label_attr' => array('class' => 'naslov'),
            ));
        $builder->add('startTime', TimeType::class, array(
                    'label'=>'Od',
                    'input'  => 'datetime',
                    'widget' => 'choice',
                     'label_attr' => array('class' => 'naslov'),
       
            
           ));
        $builder->add('finishTime',TimeType::class, array(
                    'label'=>'Do',
                    'input'  => 'datetime',
                    'widget' => 'choice',
                     'label_attr' => array('class' => 'naslov'),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Term',
        ));
    }
}
