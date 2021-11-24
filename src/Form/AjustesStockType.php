<?php

namespace App\Form;

use App\Entity\AjustesStock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjustesStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('cantidad')
            ->add('observaciones')
            ->add('stock_anterior')
            ->add('motivo')
            ->add('usuario')
            ->add('producto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AjustesStock::class,
        ]);
    }
}
