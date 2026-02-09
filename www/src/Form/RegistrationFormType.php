<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ prénom : zone de texte pour que l'utilisateur entre son pseudonyme
            ->add('name', TextType::class, [
                // 'label' : le texte affiché à côté du champ dans le formulaire
                'label' => 'Prénom',
                // 'attr' : les attributs HTML à ajouter à l'élément <input>
                'attr' => [
                    // 'class' : classe CSS pour styliser le champ
                    'class' => 'form-input',
                    // 'placeholder' : texte d'indication visible avant la saisie
                    'placeholder' => 'Votre prénom',
                    // 'autocomplete' : aide le navigateur pour remplir automatiquement (username = nom d'utilisateur)
                    'autocomplete' => 'name',
                ],
            ])
            // Champ nom : zone de texte pour que l'utilisateur entre son pseudonyme
            ->add('lastname', TextType::class, [
                // 'label' : le texte affiché à côté du champ dans le formulaire
                'label' => 'Nom de famille',
                // 'attr' : les attributs HTML à ajouter à l'élément <input>
                'attr' => [
                    // 'class' : classe CSS pour styliser le champ
                    'class' => 'form-input',
                    // 'placeholder' : texte d'indication visible avant la saisie
                    'placeholder' => 'Votre nom',
                    // 'autocomplete' : aide le navigateur pour remplir automatiquement (username = nom d'utilisateur)
                    'autocomplete' => 'lastname',
                ],
            ])
            // Champ email : zone de texte spécialisée pour les adresses email
            ->add('email', EmailType::class, [
                // 'label' : texte affiché pour le champ
                'label' => 'Email',
                // 'attr' : attributs HTML pour l'élément <input type="email">
                'attr' => [
                    // 'class' : classe CSS pour le style
                    'class' => 'form-input',
                    // 'placeholder' : texte d'aide visible avant la saisie
                    'placeholder' => 'votre@email.com',
                    // 'autocomplete' : aide le navigateur à remplir avec l'email existant
                    'autocomplete' => 'email',
                ],
            ])
            // Champ pseudo : zone de texte pour que l'utilisateur entre son pseudonyme
            ->add('phone_number', TextType::class, [
                // 'label' : le texte affiché à côté du champ dans le formulaire
                'label' => 'Numéro de téléphone',
                // 'attr' : les attributs HTML à ajouter à l'élément <input>
                'attr' => [
                    // 'class' : classe CSS pour styliser le champ
                    'class' => 'form-input',
                    // 'placeholder' : texte d'indication visible avant la saisie
                    'placeholder' => 'Votre numéro de téléphone',
                    // 'autocomplete' : aide le navigateur pour remplir automatiquement (username = nom d'utilisateur)
                    'autocomplete' => 'phone_number',
                ],
            ])
            // Champ pseudo : zone de texte pour que l'utilisateur entre son pseudonyme
            ->add('identity_number', TextType::class, [
                // 'label' : le texte affiché à côté du champ dans le formulaire
                'label' => 'Numéro de carte d\'identité',
                // 'attr' : les attributs HTML à ajouter à l'élément <input>
                'attr' => [
                    // 'class' : classe CSS pour styliser le champ
                    'class' => 'form-input',
                    // 'placeholder' : texte d'indication visible avant la saisie
                    'placeholder' => 'Votre numéro de carte d\'identité',
                    // 'autocomplete' : aide le navigateur pour remplir automatiquement (username = nom d'utilisateur)
                    'autocomplete' => 'identity_number',
                ],
            ])
            // Champ mot de passe : utilise RepeatedType pour demander le mot de passe 2 fois
            ->add('plainPassword', RepeatedType::class, [
                // 'type' : le type de champ à répéter (PasswordType = masque le texte)
                'type' => PasswordType::class,
                // 'mapped' => false : ce champ n'est pas directement lié à une propriété de l'entité User
                'mapped' => false,
                // 'first_options' : configuration pour la première saisie du mot de passe
                'first_options' => [
                    // 'label' : étiquette pour le premier champ
                    'label' => 'Mot de passe',
                    // 'attr' : attributs HTML du premier input
                    'attr' => [
                        // 'class' : classe CSS pour le style
                        'class' => 'form-input',
                        // 'placeholder' : texte d'aide
                        'placeholder' => 'Minimum 6 caractères',
                        // 'autocomplete' : indique au navigateur que c'est un nouveau mot de passe
                        'autocomplete' => 'new-password',
                    ],
                ],
                // 'second_options' : configuration pour la deuxième saisie du mot de passe (confirmation)
                'second_options' => [
                    // 'label' : étiquette pour le second champ
                    'label' => 'Confirmer le mot de passe',
                    // 'attr' : attributs HTML du second input
                    'attr' => [
                        // 'class' : classe CSS pour le style
                        'class' => 'form-input',
                        // 'placeholder' : texte d'aide
                        'placeholder' => 'Répétez le mot de passe',
                        // 'autocomplete' : indique au navigateur que c'est un nouveau mot de passe
                        'autocomplete' => 'new-password',
                    ],
                ],
                // 'invalid_message' : message d'erreur si les 2 mots de passe ne correspondent pas
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                // 'constraints' : tableau des règles de validation à appliquer
                'constraints' => [
                    // NotBlank : le champ ne peut pas être vide
                    new NotBlank(message: 'Veuillez entrer un mot de passe'),
                    // Length : vérifie la longueur du mot de passe
                    new Length(
                        // 'min' : nombre minimum de caractères
                        min: 6,
                        // 'minMessage' : message d'erreur si le mot de passe est trop court ({{ limit }} = 6)
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // 'max' : nombre maximum de caractères autorisé
                        max: 255
                    ),
                ],
            ])
            // Champ checkbox pour l'acceptation des conditions d'utilisation
            ->add('agreeTerms', CheckboxType::class, [
                // 'mapped' => false : ce champ n'existe pas dans l'entité User (juste pour le formulaire)
                'mapped' => false,
                // 'label' : texte affiché à côté de la case à cocher
                'label' => 'J\'accepte les conditions d\'utilisation',
                // 'constraints' : règles de validation à appliquer
                'constraints' => [
                    // IsTrue : la case DOIT être cochée (true) pour valider le formulaire
                    new IsTrue(message: 'Vous devez accepter les conditions d\'utilisation.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Définit la classe d'entité associée à ce formulaire
        // Le formulaire va créer et remplir un objet User
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
