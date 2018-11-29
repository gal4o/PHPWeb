<?php

namespace DentalClinicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullName', TextType::class)
            ->add('phone', TextType::class)
            ->add('note', TextType::class )
            ->add('photo', FileType::class, ['data' => null]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('data_class' =>
            'DentalClinicBundle\Entity\Patient'));
    }
//
//    public function getBlockPrefix()
//    {
//        return 'dental_clinic_bundle_patient_type';
//    }
}
