<?php

namespace App\Form;

use App\Entity\Usuarios;
use App\Entity\Almacenes;
use App\Repository\AlmacenesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('email')
            ->add('direccion')
            ->add('rol')
            ->add('almacen', EntityType::class, [
                'class' => Almacenes::class,
                'query_builder' => function (AlmacenesRepository $a) {
                    return $a->createQueryBuilder('a')
                        ->andwhere('a.eliminado = 0')
                        ->orderBy('a.nombre', 'ASC');
                }
            ])
            ->add('password', PasswordType::class, [
                'always_empty' => true
            ])
            ->add('habilitado')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
