<?php

namespace App\Form;

use App\Entity\Charge;
use App\Entity\EyesColor;
use App\Entity\Gender;
use App\Entity\HairColor;
use App\Entity\Nationality;
use App\Entity\People;
use App\Entity\SkinColor;
use App\Entity\SpokenLangage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Prénom de la personne",
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => "John"
                ],
                'constraints' => [
                    new NotBlank(message: "Le prénom ne peut pas être vide"),
                    new Length(
                        max: 100,
                        maxMessage: "Le prénom ne peut pas dépasser {{ limit }} caractères."
                    )
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => "Nom de la personne",
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => "Doe"
                ],
                'constraints' => [
                    new NotBlank(message: "Le nom ne peut pas être vide"),
                    new Length(
                        max: 150,
                        maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
                    )
                ]
            ])
            ->add('birthDate', DateTimeType::class, [
                'label' => "Date de naissance",
                'widget' => 'single-text',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('height', TextType::class, [
                'label' => "Taille (optionnel)",
                'required' => false,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('weight', TextType::class, [
                'label' => "Poids (optionnel)",
                'required' => false,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('isCaptured')
            ->add('features', TextareaType::class, [
                'label' => "Caractéristiques supplémentaires (optionnel)",
                'required' => false,
                'attr' => [
                    'class' => 'form-textarea',
                    'rows' => 6,
                    'placeholder' => "Soyez le plus précis possible."
                ]
            ])
            ->add('birthPlace', TextType::class, [
                'label' => "Lieu de naissance",
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => "Sevran, France"
                ]
            ])
            ->add('researchBy', TextType::class, [
                'label' => "Personne recherchée par :",
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('hairColor', EntityType::class, [
                'class' => HairColor::class,
                'label' => "Couleur des cheveux (optionnel)",
                'choice_label' => 'label',
                'required' => false,
                'placeholder' => "Choisissez une couleur de cheveux",
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'label' => "Sexe de la personne (optionnel)",
                'choice_label' => 'label',
                'required' => false,
                'placeholder' => "Choisissez un sexe",
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('eyesColor', EntityType::class, [
                'class' => EyesColor::class,
                'label' => "Couleur des yeux (optionnel)",
                'choice_label' => 'label',
                'required' => false,
                'placeholder' => "Choisissez une couleur des yeux",
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('skinColor', EntityType::class, [
                'class' => SkinColor::class,
                'label' => "Couleur de peau (optionnel)",
                'choice_label' => 'label',
                'required' => false,
                'placeholder' => "Choisissez une couleur de peau",
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('nationalities', EntityType::class, [
                'class' => Nationality::class,
                'label' => "Nationnalité",
                'choice_label' => 'label',
                'placeholder' => "Choisissez une/des nationalités",
                'attr' => [
                    'class' => 'form-select'
                ],
                'multiple' => true,
            ])
            ->add('charges', EntityType::class, [
                'class' => Charge::class,
                'label' => "Chef(s) d'accusation",
                'choice_label' => 'label',
                'placeholder' => "Choisissez un/des chefs d'accusation",
                'attr' => [
                    'class' => 'form-select'
                ],
                'multiple' => true,
            ])
            ->add('spokenLangages', EntityType::class, [
                'class' => SpokenLangage::class,
                'label' => "Langue(s) parlée(s)",
                'choice_label' => 'label',
                'placeholder' => "Choisissez une/des langue(s)",
                'attr' => [
                    'class' => 'form-select'
                ],
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => People::class,
        ]);
    }
}
