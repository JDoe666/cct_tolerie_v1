<?php

namespace App\Form\Frontend;

use App\Entity\Devis;
use App\Entity\UserAddress;
use App\Form\Frontend\UserDevisImageFormType;
use App\Repository\UserAddressRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserDevisFormType extends AbstractType
{
    public function __construct(
        private Security $security
        )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if ($options['isEdit']) {
            // Affiche uniquement le champ "status" si on est sur la route "admin/devis/id/update"
            $builder->add('status', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    Devis::STATUS_ACCEPTED => Devis::STATUS_ACCEPTED,
                    Devis::STATUS_FINISHED => Devis::STATUS_FINISHED,
                    Devis::STATUS_CANCELED => Devis::STATUS_CANCELED,
                    Devis::STATUS_DENIED => Devis::STATUS_DENIED,
                ],
                'required' => true,
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
            ]);
        } else {
            // Sinon, affiche les autres champs
            $builder
                ->add('firstname', TextType::class, [
                    'label' => 'Prénom',
                    'required' => true,
                ])
                ->add('lastname', TextType::class, [
                    'label' => 'Nom',
                    'required' => true,
                ])
                ->add('address', EntityType::class, [
                    'label' => 'Adresse',
                    'placeholder' => 'Renseignez votre adresse',
                    'class' => UserAddress::class,
                    'choice_label' => 'address',
                    'expanded' => false,
                    'multiple' => false,
                    'autocomplete' => true,
                    'query_builder' => function (UserAddressRepository $userAddressRepository) use ($user) {
                        return $userAddressRepository->createQueryBuilder('u')
                            ->where(':user MEMBER OF u.user')
                            ->setParameter('user', $user);
                    },
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'required' => true,
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Veuillez ajouter une description',
                    ]
                ])
                ->add('devisImages', CollectionType::class, [
                    'label' => 'Images',
                    'entry_type' => UserDevisImageFormType::class,
                    'entry_options' => [
                        'label' => false,
                    ],
                    'constraints' => [
                        new Assert\NotNull([
                            'message' => "Nous avons besoin d'une ou plusieurs images.",
                        ]),
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'delete_empty' => true,
                    'required' => true,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
            'isEdit' => false,
        ]);
    }
}