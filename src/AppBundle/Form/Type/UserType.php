<?php
// src/AppBundle/Form/Type/UserType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use \Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;




class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $admin=$options['admin'];
        
        $builder->add('login', TextType::class, array( 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'username', 'class'=>'form-control naslov'),));
        $builder->add('email', EmailType::class, array( 'label_attr' => array('class' => 'sr-only'),'attr' => array('placeholder'=>'email', 'class'=>'form-control naslov'),));
        $builder->add('person',
                    PersonType::class,
                    array( 
                        'label_attr' => array('class' => 'sr-only'),
                        'validation_groups' => array('Administration'),
                        'admin'=>$admin,
                        ));
        if($admin)//only admin can change role
        {
            $builder->add('role', EntityType::class,array(
                'label' => 'pers_info.role',
                'class' => 'AppBundle:Role',
                'required'=>true,
                'label_attr' => array('class'=>'naslov '),
                'attr' => array('class'=>'form-control')
                ));
            
            $builder->add('myCourses', CollectionType::class, array(
                'entry_type' => Teacher2courseType::class,
                'allow_add'  => true,
                'allow_delete'=>true,
                'by_reference' => false,
                'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                    'attr'      => array('class' => ' course_wrapper naslov '))
        ));
            
            $builder->add('participations', CollectionType::class, array(
                'entry_type' => User2course2Type::class,
                'allow_add'  => true,
                'allow_delete'=>true,
                'by_reference' => false,
                'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                    'attr'      => array('class' => ' participation_wrapper naslov material_style '))
        ));
            
            $builder->add('programs', CollectionType::class, array(
                'entry_type' => User2programType::class,
                'allow_add'  => true,
                'allow_delete'=>true,
                'by_reference' => false,
                'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                    'attr'      => array('class' => ' program_wrapper naslov material_style '))
        ));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'admin'=>FALSE,
            'cascade_validation' => true,
            'validation_groups' => array('Administration', 'Registration'),
        ));
        
        $resolver->setRequired(array('admin'));
        
        
    }

    public function getName()
    {
        return 'user';
    }
}