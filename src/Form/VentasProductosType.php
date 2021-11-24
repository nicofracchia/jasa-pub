<?php

namespace App\Form;

use App\Entity\VentasProductos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentasProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad')
            ->add('costo')
            ->add('venta')
            ->add('producto')
            ->add('unidad_medida')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VentasProductos::class,
        ]);
    }
}
