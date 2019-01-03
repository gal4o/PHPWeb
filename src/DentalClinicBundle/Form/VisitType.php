<?php

namespace DentalClinicBundle\Form;

use DentalClinicBundle\Entity\Tariff;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('note', TextType::class);

        $builder -> addEventListener ( FormEvents :: PRE_SET_DATA , function ( FormEvent $event ) {
            $visit = $event -> getData ();
            $form = $event -> getForm ();

            if ( ! $visit || null === $visit -> getId ()) {
                $form->add('manipulations', EntityType::class, [
                    'class' => Tariff::class,
                    'choice_label' => 'treatment',
                    'multiple' => true,
                ]);
            }});

        $builder->add('save', SubmitType::class);
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
