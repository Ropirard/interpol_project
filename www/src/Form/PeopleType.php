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
use App\Repository\ChargeRepository;
use App\Repository\NationalityRepository;
use App\Repository\SpokenLangageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isMissing = $options['data'] instanceof People && $options['data']->getType() === 'Disparu';

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
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('height', TextType::class, [
                'label' => "Taille en cm (optionnel)",
                'required' => false,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('weight', TextType::class, [
                'label' => "Poids en kg (optionnel)",
                'required' => false,
                'attr' => [
                    'class' => 'form-input'
                ]
            ])
            ->add('isCaptured', CheckboxType::class, [
                'label' => $isMissing ? 'Personne retrouvée ?' : 'Personne capturée ?',
                'required' => false,
            ])
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
            ->add('type', ChoiceType::class, [
                'label' => "Type de personne",
                'choices' => [
                    'Criminel' => 'Criminel',
                    'Disparu' => 'Disparu',
                ],
                'placeholder' => 'Choisissez un type',
                'attr' => [
                    'class' => 'form-select'
                ],
                'constraints' => [
                    new NotBlank(message: "Le type ne peut pas être vide")
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
                'label' => "Nationalité (maximum 2)",
                'choice_label' => 'label',
                'query_builder' => function (NationalityRepository $repo) {
                    return $repo->createQueryBuilder('n')->orderBy('n.label', 'ASC');
                },
                'constraints' => [
                    new Count(
                        max: 2,
                        maxMessage: 'Vous pouvez sélectionner au maximum {{ limit }} nationalités.'
                    )
                ],
                'attr' => [
                    'class' => 'checkbox-grid'
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('charges', EntityType::class, [
                'class' => Charge::class,
                'label' => "Chef(s) d'accusation",
                'choice_label' => 'label',
                'query_builder' => function (ChargeRepository $repo) {
                    return $repo->createQueryBuilder('c')->orderBy('c.label', 'ASC');
                },
                'attr' => [
                    'class' => 'checkbox-grid'
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('spokenLangages', EntityType::class, [
                'class' => SpokenLangage::class,
                'label' => "Langue(s) parlée(s)",
                'choice_label' => 'label',
                'query_builder' => function (SpokenLangageRepository $repo) {
                    return $repo->createQueryBuilder('s')->orderBy('s.label', 'ASC');
                },
                'attr' => [
                    'class' => 'checkbox-grid'
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('files', FileType::class, [
                'label' => "Médias (optionnel)",
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'form-input',
                    'accept' => 'image/*'
                ]
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
