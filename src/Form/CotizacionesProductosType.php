<?php

namespace App\Form;

use App\Entity\CotizacionesProductos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotizacionesProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id_cotizacion')
            ->add('id_producto')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CotizacionesProductos::class,
        ]);
    }
}
