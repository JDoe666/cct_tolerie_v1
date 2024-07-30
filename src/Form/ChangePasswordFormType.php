<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('plainPassword', RepeatedType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'type' => PasswordType::class,
            'invalid_message' => 'Les mots de passe doivent être identiques.',
            'first_options' => [
                'label' => 'Mot de passe',
                'attr' => [
                    'autocomplete' => 'Nouveau mot de passe',
                    'placeholder' => '********'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/',
                        'message' => 'Votre mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial, et faire plus de 8 caractères.'
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ],
            'second_options' => [
                'label' => 'Répétez votre mot de passe',
                'attr' => [
                    'placeholder' => '********'
                ]
            ],
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}