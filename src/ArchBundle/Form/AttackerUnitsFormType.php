<?php

namespace ArchBundle\Form;

use ArchBundle\Entity\Unit;
use ArchBundle\Entity\UnitName;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttackerUnitsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('count', NumberType::class,['required'=>true ,'invalid_message'=>'Count field not correct!'])
            ->add('unitName', EntityType::class, [
                'class' => UnitName::class ,'required'=>true ,'disabled'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class'=>Unit::class]);
    }

    public function getName()
    {
        return 'arch_bundle_attacker_units_form_type';
    }
}
