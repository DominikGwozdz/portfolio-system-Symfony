<?php

namespace App\Form;

use App\Entity\Gallery;
use App\Entity\GalleryItem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class GalleryItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture', FileType::class, [
                'mapped' => false,
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zapisz',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GalleryItem::class,
        ]);
    }
}
