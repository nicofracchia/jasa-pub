<?php

namespace App\Form;

use App\Entity\Productos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ChoiceList;


class ProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('codigo_barras')
            ->add('categoria')
            ->add('costo')
            ->add('porcentaje_costo')
            ->add('precio_final')
            ->add('stock_actual')
            ->add('stock_minimo')
            ->add('id_unidad_medida')
            ->add('habilitado')
            ->add('descripcion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Productos::class,
        ]);
    }
}
