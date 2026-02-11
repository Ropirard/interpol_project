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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('lastname')
            ->add('birthDate')
            ->add('height')
            ->add('weight')
            ->add('isCaptured')
            ->add('features')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('birthPlace')
            ->add('researchBy')
            ->add('type')
            ->add('hairColor', EntityType::class, [
                'class' => HairColor::class,
                'choice_label' => 'id',
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
            'data_class' => People::class,
        ]);
    }
}
