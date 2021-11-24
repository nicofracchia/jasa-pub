<?php

namespace App\Form;

use App\Entity\Combos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CombosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('porcentaje_costo')
            ->add('precio_final')
            ->add('descripcion')
            ->add('habilitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Combos::class,
        ]);
    }
}
