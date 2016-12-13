<?php

namespace ArchBundle\Form;

use ArchBundle\Entity\Base;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('units',CollectionType::class,['entry_type'=>AttackerUnitsFormType::class])
            ->add('Attack',SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>Base::class]);
    }

    public function getName()
    {
        return 'arch_bundle_attack_form_type';
    }
}
