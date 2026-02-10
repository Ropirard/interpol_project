<?php

namespace App\Form;

use App\Entity\SpokenLangage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SpokenLangageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Langue',
                'attr' => [
                    'placeholder' => 'Entrez la langue',
                    'maxlength' => 150,
                ],
                'constraints' => [
                    new NotBlank(message: "La categorie ne peut pas être vide"),
                    new Length(
                        max: 150,
                        maxMessage: "Le nom de la categorie ne peut pas dépasser {{ limit }} caractères"
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SpokenLangage::class,
        ]);
    }
}
