<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\TypeAccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('balance')
            ->add(
                'accountType',
                EntityType::class,
                [
                    'class' => TypeAccount::class,
                    'choice_label' => 'description',
                    'label' => 'Categoria: '
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
