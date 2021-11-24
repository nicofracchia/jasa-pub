<?php

namespace App\Form;

use App\Entity\Proveedores;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProveedoresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('cuit')
            ->add('telefono')
            ->add('mail')
            ->add('direccion')
            ->add('notas')
            ->add('habilitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Proveedores::class,
        ]);
    }
}
