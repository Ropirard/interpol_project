<?php

namespace App\Form;

use App\Entity\Charge;
use App\Entity\Criminal;
use App\Entity\EyesColor;
use App\Entity\Gender;
use App\Entity\HairColor;
use App\Entity\Nationality;
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

class CriminalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Prénom du criminel",
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
                'label' => "Nom du criminel",
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
                'label' => "Criminel recherché(e) par :",
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
                'choice_label' => 'id',
            ])
            ->add('eyesColor', EntityType::class, [
                'class' => EyesColor::class,
                'choice_label' => 'id',
            ])
            ->add('skinColor', EntityType::class, [
                'class' => SkinColor::class,
                'choice_label' => 'id',
            ])
            ->add('nationalities', EntityType::class, [
                'class' => Nationality::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('charges', EntityType::class, [
                'class' => Charge::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('spokenLangages', EntityType::class, [
                'class' => SpokenLangage::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Criminal::class,
        ]);
    }
}
