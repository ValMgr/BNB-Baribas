<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Compte courant' => 'Compte courant',
                    'Livret A' => 'Livret A',
                    'Livret Jeune' => 'Livret Jeune',
                    'Livret d’épargne populaire' => 'Livret d’épargne populaire',
                    'Compte épargne logement' => 'Compte épargne logement',
                    'Plan d’épargne retraite populaire' => 'Plan d’épargne retraite populaire'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
