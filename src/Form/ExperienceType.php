<?php

namespace App\Form;

use App\Entity\Experience;
use Doctrine\DBAL\Types\SmallIntType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('yearsNumber', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'label' => 'Nombre d\'années :',
                'help' => 'Indiquez une expérience en nombre d\'années ex : de 1 à 2 ans',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
