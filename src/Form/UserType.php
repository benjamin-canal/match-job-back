<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email :'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle :',
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDATE',
                    'Recruteur' => 'ROLE_RECRUITER',
                    'Admin' => 'ROLE_ADMIN',
                ],
                // Multiple choice
                'multiple' => false,
                // Radios buttons
                'expanded' => true,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                // The user (whose mapped on the form) is here
                $user = $event->getData();
                // We get the form (because no access to variables outsite de anonymous function)
                $form = $event->getForm();

                // Add or Edit ?
                // New user does not have id!
                if ($user->getId() === null) {
                    // Add
                    $form->add('password', PasswordType::class, [
                        'constraints' => [
                            new NotBlank(),
                            new Regex("/^(?=.*[0-9])(?=.*[A-Z])(?=.*[a-z])(?=.*['_', '-', '|', '%', '&', '*', '=', '@', '$', '!']).{8,}$/")
                        ],
                        'help' => 'Au moins 8 caractères,
                            au moins une minuscule
                            au moins une majuscule
                            au moins un chiffre
                            au moins un caractère spécial parmi _, -, |, %, &, *, =, @, $, !'
                    ]);
                } else {
                    // Edit
                    $form->add('password', PasswordType::class, [
                        'empty_data' => '',
                        'attr' => [
                            'placeholder' => 'Laissez vide si inchangé'
                        ],
                        // @link https://symfony.com/doc/current/reference/forms/types/text.html#mapped
                        // This field will be not automatically mapped
                        // the $password property of $user will be not updated
                        'mapped' => false,
                    ]);
                }
            });
        
        // Roles field datatransformer 
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            // Entity => Form (form display)
            function ($rolesAsArray) {
                // transform the array to a string
                return implode(', ', $rolesAsArray);
            },
            // Form => Entity (form process)
            function ($rolesAsString) {
                // transform the string back to an array
                return explode(', ', $rolesAsString);
            }
        )); 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
