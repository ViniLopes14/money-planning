<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\Category;
use App\Entity\Expense;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ExpenseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                "grouping" => true, //works only on show mode not on newAction
                'attr' => array(
                    "class" => "number",
                    "min" => 0,
                    "step" => 0.100,
                    "placeholder" => "0.000",

  )
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'
            ])
            ->add('note')
            ->add('repetitionType', ChoiceType::class, [
                'choices' => [
                    'Única' => 1,
                    'Parcelada' => 2,
                    'Fixa' => 3,
                ],
            ])
            ->add('repetitionPeriodicity', ChoiceType::class, [
                'choices' => [
                    'Diária' => 1,
                    'Semanal' => 2,
                    'Mensal' => 3,
                    'Anual' => 4,
                ],
            ])
            ->add('repetitionInstallment')
            ->add('description')
            ->add(
                'account',
                EntityType::class,
                [
                    'class' => Account::class,
                    'choice_label' => 'description',
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'description',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Expense::class,
        ]);
    }
}
