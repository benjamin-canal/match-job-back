<?php

namespace App\Form;

use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TechnologyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('technologyName', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => 'Nom :',
                'help' => 'Indiquez un nom de technologie'
            ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Couleur du fond',
            ])
            ->add('textColor', ColorType::class, [
                'label' => 'Couleur du texte',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Technology::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
