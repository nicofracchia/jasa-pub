<?php

namespace App\Form;

use App\Entity\MovimientosCaja;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientosCajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('movimiento')
            ->add('tipoMovimiento')
            ->add('monto')
            ->add('observaciones')
            ->add('caja')
            ->add('creador')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MovimientosCaja::class,
        ]);
    }
}
