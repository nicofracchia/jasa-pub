<?php

namespace App\Form;

use App\Entity\VentasPagos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VentasPagosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('monto')
            ->add('comprobante')
            ->add('url_comprobante')
            ->add('medio_pago')
            ->add('venta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VentasPagos::class,
        ]);
    }
}
