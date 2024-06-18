<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['is_authenticated']) {
            $builder
                ->add('name', TextType::class, [
                    'attr' => ['class' => 'form-control']
                ])
                ->add('email', EmailType::class, [
                    'attr' => ['class' => 'form-control']
                ]);
        }

        $builder
            ->add('subject', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('message', TextareaType::class, [
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'is_authenticated' => false,
        ]);
    }
}
