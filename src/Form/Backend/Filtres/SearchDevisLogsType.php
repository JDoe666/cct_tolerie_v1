<?php

namespace App\Form\Backend\Filtres;

use App\Entity\Devis;
use App\Entity\Filtres\DevisLogsFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchDevisLogsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actions', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Créations' => "Création",
                    'Modifications' => 'Modification',
                    'Suppressions' => 'Suppression',
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

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DevisLogsFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}