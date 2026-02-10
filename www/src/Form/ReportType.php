<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\People;
use App\Entity\Report;
use App\Entity\TypeReport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeReport', EntityType::class, [
                'label' => 'Type de signalement',
                'class' => TypeReport::class,
                'choice_label' => 'label',
                'placeholder' => 'Sélectionnez un motif...',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Motif du signalement',
                'attr' => [
                    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors outline-none resize-none',
                    'placeholder' => 'Décrivez en détail votre signalement',
                    'rows' => 5,
                    'minlength' => 10,
                    'maxlength' => 2000
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Report::class,
            'csrf_token_id' => 'submit'
        ]);
    }
}
