<?php

namespace App\Form\Backend\Filtres;

use App\Entity\Devis;
use App\Entity\Filtres\DevisFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDevisType extends AbstractType
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
                ])
                ->add('status', ChoiceType::class, [
                    'label' => false,
                    'placeholder' => 'Choisir un status',
                    'choices' => [
                        Devis::STATUS_WAITING => Devis::STATUS_WAITING,
                        Devis::STATUS_ACCEPTED => Devis::STATUS_ACCEPTED,
                        Devis::STATUS_FINISHED => Devis::STATUS_FINISHED,
                        Devis::STATUS_CANCELED => Devis::STATUS_CANCELED,
                        Devis::STATUS_DENIED => Devis::STATUS_DENIED,
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'autocomplete' => true,
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'required' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DevisFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}