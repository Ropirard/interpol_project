<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\People;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Entrez le titre de l\'article'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Le titre ne peut pas être vide.'
                    ),
                    new Length(
                        max: 255,
                        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères.'
                    )
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'attr' => [
                    'class' => 'form-textarea',
                    'rows' => 5,
                    'placeholder' => 'Rédigez le contenu ici...'
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Le contenu ne peut pas être vide.'
                    ),
                    new Length(
                        min: 20,
                        minMessage: 'Le contenu doit faire au moins {{ limit }} caractères.',
                        max: 5000,
                        maxMessage: 'Le contenu ne peut pas dépasser {{ limit }} caractères.'
                    )
                ]
            ])
            ->add('people', EntityType::class, [
                'class' => People::class,
                'choice_label' => function (People $people) {
                    return $people->getLastname() . ' ' . $people->getName();
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'by_reference' => false,
            ])
            ->add('files', FileType::class, [
                'label' => "Fichiers (optionnel)",
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-input',
                    'accept' => 'image/*'
                ],
                'constraints' => [
                    new All(
                        constraints: [
                            new File(
                                maxSize: '5M',
                                mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml', 'image/avif'],
                                mimeTypesMessage: "Type de fichier non autorisé. Type acceptés : JPEG, PNG, WEBP, GIF, AVIF et SVG"
                            )
                        ]
                    )
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
