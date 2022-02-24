<?php

namespace App\Form;

use App\Entity\Virement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Account;

class VirementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('rib',TextType::class, array(
                "mapped" => false,
            ))
            ->add('origine', EntityType::class, [
                'class' => Account::class,
                'choices' => $options['origines']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Virement::class,
            'origines' => ''
        ]);
    }
}
