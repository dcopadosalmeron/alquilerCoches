<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni',null,[
                'label' => 'DNI',
                'attr' => array(
                    'placeholder' => 'Introduzca un DNI',
                )
            ])
            ->add('nombre',null,[
                'label' => 'Nombre',
                'attr' => array(
                    'placeholder' => 'Introduzca un nombre',
                )
            ])
            ->add('apellidos',null,[
                'label' => 'Apellidos',
                'attr' => array(
                    'placeholder' => 'Introduzca unos apellidos',
                )
            ])
            ->add('fechaNacimiento',TextType::class,[
                'label' => 'Fecha de nacimiento',
                'attr' => array(
                    'class' => 'fecha',
                    'placeholder' => 'Seleccione una fecha inicial',
                )
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class
        ]);
    }
}