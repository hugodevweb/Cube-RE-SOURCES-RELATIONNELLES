<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre'])
            ->add('corps', TextareaType::class, ['required' => false, 'label' => 'Contenu de l\'article'])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'multiple' => true,
                'choice_label' => 'nom',
                'label' => 'Sélectionnez une catégorie',
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'nom',
                'label' => 'Type du fichier joint',
            ])
            ->add('ressources', FileType::class, [
                'multiple' => true,
                'required' => false,
                'label' => 'Ressource(s) jointe(s)',
            ])
            ->add('valider', SubmitType::class, ['label' => 'Créer l\'article'])
        ;

        ini_restore('upload_max_filesize');
        ini_restore('post_max_size');
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
    //     $resolver->setDefaults([
    //         // 'data_class' => Article::class,
    //     ]);
    // }
}

