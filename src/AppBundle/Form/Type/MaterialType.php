<?php
// src/AppBundle/Form/Type/MaterialType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label'=>'material.name', 'label_attr' => array('class' => 'naslov'),'attr' => array('class'=>'form-control','required'=>TRUE),));
        $builder->add('file', FileType::class, array('label'=>'material.file', 'label_attr' => array('class' => 'naslov'),'attr' => array( 'class'=>'form-control', 'required'=>FALSE),));
        $builder->add('teacherMaterials', CheckboxType::class, array('label'=>'material.trainersMaterial', 'label_attr' => array('class' => 'naslov'),'required'=>FALSE));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Material'
        ));
    }

    public function getName()
    {
        return 'material_type';
    }
}