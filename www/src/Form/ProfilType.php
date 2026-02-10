<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent',
                    'placeholder' => 'Votre adresse email',
                ],
                'constraints' => [
                    new NotBlank(message: 'L\'email est obligatoire.'),
                    new Email(message: 'L\'email n\'est pas valide'),
                    new Length(max: 180, maxMessage: 'L\'email ne peut pas dépasser {{ limit }} caractères'),
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent',
                    'placeholder' => 'Votre prénom',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le prénom est obligatoire.'),
                    new Length(max: 100, maxMessage: 'Le prénom ne peut pas dépasser {{ limit }} caractères'),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent',
                    'placeholder' => 'Votre nom',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le nom est obligatoire.'),
                    new Length(max: 150, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'),
                ],
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'Numéro',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent',
                    'placeholder' => 'Votre numéro',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le numéro est obligatoire.'),
                    new Length(max: 10, maxMessage: 'Le numéro ne peut pas dépasser {{ limit }} chiffres'),
                ],
            ])
            ->add('identity_number', TextType::class, [
                'label' => 'Numéro d\'identité',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent',
                    'placeholder' => 'Votre numéro d\'identité',
                ],
                'constraints' => [
                    new NotBlank(message: 'Le numéro d\'identité est obligatoire.'),
                    new Length(max: 20, maxMessage: 'Le numéro d\'identité ne peut pas dépasser {{ limit }} chiffres'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity(
                    fields: 'email',
                    message: 'Cet email est déjà utilisé.',
                ),
                new UniqueEntity(
                    fields: 'phone_number',
                    message: 'Ce numéro est déjà attribué.',
                ), 
                new UniqueEntity(
                    fields: 'identity_number',
                    message: 'Ce numéro d\identité est déjà attribué.',
                ),
            ],
        ]);
    }
}
