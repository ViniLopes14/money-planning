<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Revenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RevenueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('note')
            ->add('repetitionType')
            ->add('repetitionPeriodicity')
            ->add('repetitionInstallment')
            ->add('description')
            ->add(
                'account',
                EntityType::class,
                [
                    'class' => Account::class,
                    'choice_label' => 'description',
                    'label' => 'Conta: '
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'description',
                    'label' => 'Categoria: '
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Revenue::class,
        ]);
    }
}
