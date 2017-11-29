<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OpenCourseType extends AbstractType
{
    protected $courseId;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                
            ->add('address', AddressType::class,array( 
                'label_attr' => array(),
                'label' => 'opencourse.address',
                'attr' => array('class'=>'multicolumns'))  
                )

  
            ->add(
                    'category',ChoiceType::class, array(
                    'label_attr' => array('class' => 'naslov'),
                    'label' => 'opencourse.category',
                    'choices'  => array(
                    'Redovni' => 'Redovni',
                    'Izvanredni otvoreni' => 'Izvanredni otvoreni',
                    'Izvanredni zatvoreni' => 'Izvanredni zatvoreni' ,
                    'Vanjski' => 'Vanjski' , 
                    'Tečajevi udruženih sindikata' => 'Tečajevi udruženih sindikata' ,),
                    'attr' => array('class'=>'form-control'))
                    )
                
            ->add(
                    'visibility',  CheckboxType::class,array(
                    'label_attr' => array('class' => 'naslov'),
                    'label' => 'opencourse.visibility'))


            ->add('confirmed',  ChoiceType::class,array(
                    'label_attr' => array('class' => 'naslov'),
                    'label' => 'opencourse.confirmed',
                    'choices'  => array(
                    'Planiran' => 1,
                    'Potvrđen' => 2,
                    'Otkazan' =>  3,
                    'Održan' =>  4,),
                    'attr' => array('class'=>'form-control'))
                    )

            ->add('registrationFee',  CheckboxType::class,array(
                    'label_attr' => array('class' => 'naslov'),
                    'label' => 'opencourse.registrationFee'))
                            
            ->add(
                    'evoluation',TextareaType::class, array(
                    'label_attr' => array('class' => 'naslov'),
                    'label' => 'opencourse.evaluation',
                    'attr' => array('class'=>'form-control myTextEditor'))
                    )
                
            ->add(
                    'description', 
                    TextareaType::class, array( 
                   'label_attr' => array('class' => 'naslov'),
                    'label' =>'opencourse.description_info', 
                   'attr' => array('class'=>'form-control'))
                    )
                
             
                
        ;
        
        $builder->add('terms', CollectionType::class, array(
            'entry_type' => TermType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' multicolumns2  term_wrapper naslov '))
        ));
        $builder ->add(
                'partMin',NumberType::class, array(
                'label_attr' => array('class' => 'naslov'),
                'label' => 'part_min',
                'attr' => array('class'=>'form-control'))
                );
        $builder ->add(
                'partMax',NumberType::class, array(
                'label_attr' => array('class' => 'naslov'),
                'label' => 'part_max',
                'attr' => array('class'=>'form-control'))
                )
                
                ->add(
                    'comment', 
                    TextareaType::class, array( 
                   'label_attr' => array('class' => 'naslov'),
                    'label' =>'opencourse.comment', 
                   'attr' => array('class'=>'form-control'))
                    )
                
                ;
        
        $builder->add('trainers', CollectionType::class, array(
            'entry_type' => Teacher2openCourseType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 
                'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' trainer_wrapper naslov '),
                'course'=>$options["course"])
        ));
        
        $builder->add('participants', CollectionType::class, array(
            'entry_type' => User2courseType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' participant_wrapper naslov material_style '))
        ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\OpenCourse',
            'course'=> NULL,
        ));
    }
    
    public function __construct($courseId=NULL) {
        $this->courseId=$courseId;
    }
}
