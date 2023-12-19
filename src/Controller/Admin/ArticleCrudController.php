<?php

namespace App\Controller\Admin;

use App\Entity\Article;

use App\Repository\UtilisateurRepository;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class ArticleCrudController extends AbstractCrudController
{
    private $utilisateurRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }
  
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre'),
            TextEditorField::new('corps'),
            Field::new('nombreVu')->onlyOnIndex(),
            BooleanField::new('validation')->onlyOnIndex(),
            AssociationField::new('categories')->onlyOnForms(),
            ChoiceField::new('utilisateur')->setChoices($this->formatUtilisateurForChoices($this->utilisateurRepository->findall()))->onlyOnForms(),
            AssociationField::new('utilisateur')
            ->setFormTypeOptions([
                'by_reference' => false,
            ])
            ->autocomplete()
            ->setCustomOptions([
                'widget' => 'native',
            ])
            ->setRequired(true)
            ->onlyOnIndex(),
        ];
    }

    private function formatUtilisateurForChoices(array $utilisateurs): array
    {
        $formattedUtilisateur = [];
        foreach ($utilisateurs as $utilisateur) {
            $formattedUtilisateur[$utilisateur->getNom()] = $utilisateur;
        }
        return $formattedUtilisateur;
    }
}
