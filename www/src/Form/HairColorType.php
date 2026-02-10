<?php

namespace App\Form;

use App\Entity\HairColor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HairColorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'Couleur de cheveux',
                'attr' => [
                    'placeholder' => 'Entrez la couleur de cheveux',
                    'maxlength' => 50,
                ],
                'constraints' => [
                    new NotBlank(message: "La categorie ne peut pas être vide"),
                    new Length(
                        max: 50,
                        maxMessage: "Le nom de le categorie ne peut pas dépasser {{ limit }} caractères"
                    )
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HairColor::class,
        ]);
    }
}
