<?php

namespace DentalClinicBundle\Form;

use DentalClinicBundle\Entity\Material;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents :: PRE_SET_DATA, function (FormEvent $event) {
            $visit = $event->getData();
            $form = $event->getForm();
            if (!$visit || null === $visit->getId()) {
                $form->add('material', EntityType::class, [
                    'class' => Material::class,
                    'choice_label' => 'name'
                ])
                    ->add('quantity', TextType::class);
            } else {
                $form->add('status', TextType::class);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DentalClinicBundle\Entity\Order',
        ));
    }

//    public function getBlockPrefix()
//    {
//        return 'dental_clinic_bundle_user_request_type';
//    }
}
