<?php
// src/AppBundle/Form/Type/Teacher2courseType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Teacher2courseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('idCourse',  EntityType::class,array(
                'label' => 'course',
                'class' => 'AppBundle:Course',
                'required'=>false,
                'label_attr' => array('class'=>'sr-only '),
                'attr' => array('class'=>'form-control')
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Teacher2course'
        ));
    }

    public function getName()
    {
        return 'teacher2course';
    }
}