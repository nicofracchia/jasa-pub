<?php

namespace App\Form;

use App\Entity\ProveedoresProductos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedoresProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('costo')
            ->add('id_proveedor')
            ->add('id_producto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProveedoresProductos::class,
        ]);
    }
}
