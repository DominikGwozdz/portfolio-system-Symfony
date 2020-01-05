<?php

namespace App\Form;

use App\Entity\About;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture',FileType::class,
                [
                    'label' => 'Zdjęcie',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/*',

                            ],
                            'mimeTypesMessage' => 'Obsługiwany format pliku to jpg'
                        ])
                    ]
                ])
            ->add('meta_description', TextType::class,
                [
                    'label' => 'Opis dla wyszukiwarek (od 120 do 158 znaków)'
                ])
            ->add('description', TextareaType::class,
                [
                    'label' => 'Opis o mnie',
                    'attr' => [
                        'rows' => '10'
                    ]
                ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Zapisz'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => About::class,
        ]);
    }
}
