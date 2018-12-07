<?php

namespace DentalClinicBundle\Form;

use DentalClinicBundle\Entity\Tariff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('manipulations', EntityType::class, [
                'class' => Tariff::class,
                'choice_label' => 'treatment',
                'multiple' => true,
//            'placeholder' => ''
        ])
            ->add('note', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class'
        =>'DentalClinicBundle\Entity\Visit',));
    }
//
//    public function getBlockPrefix()
//    {
//        return 'dental_clinic_bundle_visit_type';
//    }
}
