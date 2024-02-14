<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank; // Add this line
use Symfony\Component\Validator\Constraints\Email; // Add this line
use Symfony\Component\Validator\Constraints\Length; // Add this line
use Symfony\Component\Validator\Constraints\EqualTo; // Add this line
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom.',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom.',
                    ]),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre pseudo.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse email.',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                            // Vous pouvez ajuster le minimum et le message selon vos besoins.
                        ]),
                    ],
                ],
                'second_options' => ['label' => 'Répéter le mot de passe'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
