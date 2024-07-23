<?php

namespace App\Form\Backend\Filtres;

use App\Entity\Filtres\UserFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Utilisateur' => "ROLE_USER",
                    'Administrateur' => 'ROLE_ADMIN',
                    'Super administrateur' => 'ROLE_SUPER_ADMIN',
                ],
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Verifié',
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
            'data_class' => UserFilter::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}