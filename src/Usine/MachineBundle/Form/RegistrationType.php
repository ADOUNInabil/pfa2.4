<?php
// src/AppBundle/Form/RegistrationType.php

namespace Usine\MachineBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
        ->add('prenom')

            ->add('fonction',ChoiceType::class, array(
                'choices'  => array(
                    'Admin' => 'Admin',
                    'Superviseur' => 'Superviseur',
                    'Utilisateur' => 'Utilisateur',
                ),
            ))
            /*->add('roles',ChoiceType::class, array(
                'choices'  => array(
                    'ROLE_ADMIN' => array(ROLE_ADMIN),
                    'ROLE_USER' => array(ROLE_ADMIN),

                ),
            ))*/
        ->add('date')
        //->add('roles')
        ->add('salaire');
        $builder->add('file', FileType::class, array(
            'required' => false
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}