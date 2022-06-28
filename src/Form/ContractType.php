<?php

namespace App\Form;

use App\Entity\Contract;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du type de contrat :',
                'help' => 'Indiquez un type de contrat ex : CDI',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
            'attr' => [
                'novalidate' => 'novalidate',
            ],
        ]);
    }
}
