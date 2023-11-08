<?php

namespace App\Form;

use App\Entity\Trabajador;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrabajadorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
    
        $builder

            ->add('nombre', TextType::class)

            ->add('cargo', TextType::class)

                ->add('foto', FileType::class,[
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image file',
                        ])
                    ],
                ])

            ->add('save', SubmitType::class, array('label' => 'Enviar'));
    }     

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trabajador::class,
        ]);
    }
}
