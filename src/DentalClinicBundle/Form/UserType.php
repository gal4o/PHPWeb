<?php

namespace DentalClinicBundle\Form;

use DentalClinicBundle\Entity\ClinicBranch;
use DentalClinicBundle\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
    * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
            ->add('password', TextType::class)
            ->add('fullName', TextType::class)
            ->add('photo', FileType::class, ['data' => null])
            ->add('clinic', EntityType::class, [
                'class' => ClinicBranch::class,
                'choice_label' => 'name'
            ]);

        $builder -> addEventListener ( FormEvents :: PRE_SET_DATA , function ( FormEvent $event ) {
            $user = $event -> getData ();
            $form = $event -> getForm ();

            if ( ! $user || null === $user -> getId ()) {
                $form->add('role', EntityType::class, [
                    'class' => Role::class,
                    'choice_label' => 'name'
                ]);
            }});

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DentalClinicBundle\Entity\User'
        ));
    }
//
//    public function getBlockPrefix()
//    {
//        return 'dental_clinic_bundle_user_type';
//    }
}
