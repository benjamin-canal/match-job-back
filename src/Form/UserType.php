<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDATE',
                    'Recruteur' => 'ROLE_RECRUITER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                // Choix multiple
                'multiple' => true,
                // Des boutons radios
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
