<?php
// src/AppBundle/Form/Type/ProfileEditType.php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ProfileEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', TextType::class, array( 'label' => 'pers_info.firstname', 'label_attr' => array('class' => 'naslov'),'attr' => array( 'class'=>'form-control naslov'),));
        $builder->add('lastname', TextType::class, array( 'label' => 'pers_info.lastname', 'label_attr' => array('class' => 'naslov'),'attr' => array( 'class'=>'form-control naslov'),));
        $builder->add('birthday', DateType::class, array('label'=>'Birthday', 'label_attr' => array('class' => 'naslov'),'attr' => array('class'=>'form-control'),
    'widget' => 'choice',
    'years' => range(date('Y'), date('Y')-70),
    'format' => 'dd  M  yyyy',
'label'=>'pers_info.birthdate', 'required' => TRUE));
               /* $builder->add('birthday', 'date', array(
    'widget' => 'single_text',
    // this is actually the default format for single_text
    'format' => 'yyyy-MM-dd',
    
));*/
              
        $builder->add('gender',
                ChoiceType::class, 
                array( 
                    'choices'=>array(
                        ''=>'-',
                        'male'=>'male',
                        'female'=>'female'
                     ),
                    'label'=>'pers_info.gender', 
                    'label_attr' => array('class' => 'naslov'),
                    'attr' => array('class'=>'form-control label2')
                    )
                        ); 

        $builder->add('sendSerificateTo',
                ChoiceType::class, 
                array( 
                    'choices'=>array(
                        'pers_info.privat'=>1,
                        'pers_info.official'=>2
                     ),
                    'label'=>'pers_info.sendSerificateTo', 
                    'label_attr' => array('class' => 'naslov'),
                    'attr' => array('class'=>'form-control label2')
                    ));
        
        $builder->add('privatAddress', AddressType::class,array('label'=>'pers_info.privat', 'attr' => array('class'=>'multicolumns'))); 
        
        $builder->add('officialAddress', AddressType::class,array('label'=>'pers_info.official', 'attr' => array('class'=>'multicolumns'))); 

        $builder->add('syndicate',  EntityType::class,array(
                'label' => 'pers_info.syndicate',
                'class' => 'AppBundle:Syndicate',
                'required'=>false,
                'label_attr' => array('class'=>'naslov '),
                'attr' => array('class'=>'form-control')
                ));
        
        
        $builder->add('employer', TextType::class, array('label_attr' => array('class'=>'naslov '),'label'=>'pers_info.employer','attr' => array('class'=>'form-control '),));
  
        $builder->add('cardNum', TextType::class, array('label_attr' => array('class'=>'naslov '),'label'=>'pers_info.cardnum','attr' => array( 'class'=>'form-control'),));
        $builder->add('telephone', TextType::class, array('label_attr' => array('class'=>'naslov '),'label'=>'pers_info.telephone','attr' => array( 'class'=>'form-control '),));
        
        $builder->add('duties', CollectionType::class, array(
            'entry_type' => PersonOnDutyType::class,
            'allow_add'  => true,
            'allow_delete'=>true,
            'by_reference' => false,
            'entry_options'  => array( 'label_attr' => array('class' => ' sr-only'),
                'attr'      => array('class' => ' duty_wrapper naslov '))
        ));
        //$builder->add('Update', 'submit', array('attr' => array('class'=>'btn btn-lg btn-danger btn-block'),));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Person',
            'validation_groups' => array('Profile'),
        ));
    }

    public function getName()
    {
        return 'person';
    }
}