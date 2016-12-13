<?php

namespace ArchBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder,$options);
        $builder->add('roles',ChoiceType::class,[
            'choices'=>[
                'user'=>"ROLE_USER",
                'admin'=>'ROLE_ADMIN',
            ],
            'expanded'=>true,
            'multiple'=>true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_type'=>'ArchBundle\Entity\User']);
    }

    public function getName()
    {
        return 'arch_bundle_user_edit_type';
    }
}
