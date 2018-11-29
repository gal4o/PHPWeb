<?php

namespace DentalClinicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TariffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('treatment', TextType::class)
            ->add('price', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' => 'DentalClinicBundle\Entity\Tariff'));

    }
//
//    public function getBlockPrefix()
//    {
//        return 'dental_clinic_bundle_tariff_type';
//    }
}
