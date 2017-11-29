<?php
// src/AppBundle/Form/Type/AddressType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('street', TextType::class, array('label'=>'pers_info.street','label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'pers_info.street', 'class'=>'form-control','required'=>FALSE),));
        $builder->add('zip', TextType::class, array('label'=>'pers_info.zip', 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'pers_info.zip', 'class'=>'form-control', 'required'=>FALSE),));
        $builder->add('city', TextType::class, array('label'=>'pers_info.city', 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'pers_info.city', 'class'=>'form-control'),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Address'
        ));
    }

    public function getName()
    {
        return 'address_type';
    }
}