<?php

namespace App\Form\Backend\Filtres;

use App\Entity\Categorie;
use App\Entity\Filtres\RealisationFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchRealisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
                ]);
            // ->add('categories', EntityType::class, [
            //     'label' => 'Catégorie',
            //     'class' => Categorie::class,
            //     'expanded' => false,
            //     'multiple' => false,
            //     'autocomplete' => true,
            //     'attr' => [
            //         'class' => 'form-control',
            //     ],
            //     'required' => false,
            // ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RealisationFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}