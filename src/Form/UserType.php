<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex("/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*['!', '_', '-', '|', '%', '&', '*', '=', '@', '$']).{8,}$/")
                ],
                'help' => 'Au moins 8 caractères,
                    au moins une minuscule
                    au moins une majuscule
                    au moins un chiffre
                    au moins un caractère spécial parmi !, _, -, |, %, &, *, =, @, $'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDATE',
                    'Recruteur' => 'ROLE_RECRUITER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                // Choix multiple
                'multiple' => true,
                // Des boutons radios
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
