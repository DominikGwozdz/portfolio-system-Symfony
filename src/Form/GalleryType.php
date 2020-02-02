<?php

namespace App\Form;

use App\Entity\Gallery;
use App\Entity\GalleryCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa galerii'
            ])
            ->add('picture', FileType::class,
                [
                    'label' => 'Okładka dla galerii',
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '5M',
                            'mimeTypes' => [
                                'image/*',

                            ],
                            'mimeTypesMessage' => 'Obsługiwany format pliku to jpg'
                        ])
                    ],
                ])
            ->add('is_visible', ChoiceType::class,
                [
                    'label' => 'Czy galeria ma być widoczna dla wszystkich?',
                    'choices' => [
                        'Tak' => '1',
                        'Nie' => '0'
                    ],
                    'attr' => ['class' => 'text-black'],
                ])
            ->add('is_protected', ChoiceType::class,
                [
                    'label' => 'Czy galeria ma być chroniona hasłem?',
                    'choices' => [
                        'Tak' => '1',
                        'Nie' => '0'
                    ],
                    'attr' => ['class' => 'text-black'],
                    'choice_value' => '0'
                ])
            ->add('password', TextType::class, [
                'label' => 'Podaj hasło jeśli ma być chroniona',
                'help' => 'Nie wymagane',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Kategoria',
                'class' => GalleryCategory::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gallery::class,
        ]);
    }
}
