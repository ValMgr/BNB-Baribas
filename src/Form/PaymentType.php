<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use App\Entity\Account;


class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, array(
                'divisor' => 100,
                'attr' => ['placeholder' => '10.00']
            ))
            ->add('pUser', TextType::class, array(
                "mapped" => false,
                'attr' => ['placeholder' => 'email@test.com']
            ))
            ->add('myAccount', EntityType::class, [
                'class' => Account::class,
                'choices' => $options['accounts']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'accounts' => ''
        ]);
    }
}
