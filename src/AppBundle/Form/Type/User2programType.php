<?php
// src/AppBundle/Form/Type/user2courseType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class User2programType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('program',  EntityType::class,array(
                'label' => 'user',
                'class' => 'AppBundle:Program',
                'required'=>false,
                'label_attr' => array('class'=>'sr-only '),
                'attr' => array('class'=>'form-control')
                ));
        
        $builder->add('finished', ChoiceType::class, array(
                'label_attr' => array('class'=>'naslov '),
                'choices'  => array(
                    'Nije' => 0,
                    'Jeste' => 1,
            ),
        ));
        
        $builder->add('diploma', ChoiceType::class, array(
                'label_attr' => array('class'=>'naslov '),
                'choices'  => array(
                    'Nije' => 0,
                    'Jeste' => 1,
            ),
        ));
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User2program'
        ));
    }

    public function getName()
    {
        return 'user2course';
    }
}