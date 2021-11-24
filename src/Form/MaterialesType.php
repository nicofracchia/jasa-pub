<?php

namespace App\Form;

use App\Entity\Materiales;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterialesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('codigo_barras')
            ->add('costo')
            ->add('stock_actual')
            ->add('id_unidad_medida')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Materiales::class,
        ]);
    }
}
