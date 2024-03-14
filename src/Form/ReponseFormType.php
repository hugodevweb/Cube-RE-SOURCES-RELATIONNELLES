<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', null, [
                'label' => false, // Désactiver l'étiquette du champ
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Écrivez votre commentaire ici...', // Ajouter un placeholder au champ
                ]
            ])
            ->add('parent', HiddenType::class, [ // Ajouter un champ caché pour l'ID du commentaire parent
                'mapped' => false, // Ne pas mapper ce champ à l'entité Commentaire
                'data' => null,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
