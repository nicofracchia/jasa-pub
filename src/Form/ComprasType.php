<?php

namespace App\Form;

use App\Entity\Compras;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ComprasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha')
            ->add('precio')
            ->add('estado')
            ->add('proveedor')
            ->add('almacen')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compras::class,
        ]);
    }
}
