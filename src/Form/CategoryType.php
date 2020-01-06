<?php

namespace App\Form;

use App\Entity\GalleryCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'label' => 'Nazwa kategorii'
                ])
            ->add('picture', FileType::class,
                [
                    'label' => 'Zdjęcie dla kategorii',
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
                    'label' => 'Czy kategoria ma być widoczna dla wszystkich?',
                    'choices' => [
                        'Tak' => '1',
                        'Nie' => '0'
                    ],
                    'attr' => ['class' => 'text-black'],
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
            'data_class' => GalleryCategory::class,
        ]);
    }
}
