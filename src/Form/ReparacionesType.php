<?php

namespace App\Form;

use App\Entity\Reparaciones;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReparacionesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recepcion')
            ->add('articulo')
            ->add('marca')
            ->add('modelo')
            ->add('serial')
            ->add('tarea')
            ->add('reporte')
            ->add('tintaC')
            ->add('tintaM')
            ->add('tintaY')
            ->add('tintaCl')
            ->add('tintaMl')
            ->add('tintaBk')
            ->add('estado')
            ->add('diagnostico')
            ->add('presupuestoInicial')
            ->add('presupuestoFinal')
            ->add('observaciones')
            ->add('almacen')
            ->add('cliente')
            ->add('receptor')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reparaciones::class,
        ]);
    }
}
