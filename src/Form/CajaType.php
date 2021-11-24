<?php

namespace App\Form;

use App\Entity\Caja;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CajaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('inicio')
            ->add('cierre')
            ->add('saldoInicial')
            ->add('saldoEstimado')
            ->add('saldoFinal')
            ->add('estado')
            ->add('usuarioApertura')
            ->add('usuarioCierre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Caja::class,
        ]);
    }
}
