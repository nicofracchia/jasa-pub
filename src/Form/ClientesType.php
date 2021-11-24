<?php

namespace App\Form;

use App\Entity\Clientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('razonSocial')
            ->add('nombre')
            ->add('apellido')
            ->add('mail')
            ->add('dni')
            ->add('cuit')
            ->add('telefono')
            ->add('direccion')
            ->add('cuentaCorriente')
            ->add('habilitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Clientes::class,
        ]);
    }
}
