<?php

namespace App\Form;

use App\Entity\ComprasProductos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComprasProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad')
            ->add('compra')
            ->add('producto')
            ->add('unidad_medida')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ComprasProductos::class,
        ]);
    }
}
