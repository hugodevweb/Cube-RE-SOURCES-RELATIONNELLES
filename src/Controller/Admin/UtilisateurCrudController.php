<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Entity\Role;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class UtilisateurCrudController extends AbstractCrudController
{
    private $roleRepository;
    private $utilisateurRepository;
    
    public function __construct(RoleRepository $roleRepository, UtilisateurRepository $utilisateurRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->utilisateurRepository = $utilisateurRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = $this->roleRepository->findAll();
        $utilisateurs = $this->utilisateurRepository->findAll();
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('pseudo'),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('mail'),
            TextField::new('password')->onlyWhenCreating(),
            ChoiceField::new('role')->setChoices($this->formatRolesForChoices($roles))->onlyOnForms(),
            AssociationField::new('role')
            ->setFormTypeOptions([
                'by_reference' => false,
            ])
            ->autocomplete()
            ->setCustomOptions([
                'widget' => 'native',
            ])
            ->setRequired(true)
            ->onlyOnIndex(),
            AssociationField::new('articles')->onlyOnForms(),
        ];
    }

    private function formatRolesForChoices(array $roles): array
    {
        $formattedRoles = [];
        foreach ($roles as $role) {
            $formattedRoles[$role->getNom()] = $role;
        }
        return $formattedRoles;
    }

    private function formatArticlesForChoices(array $articles): array
    {
        $formatted_articles = [];
        foreach ($articles as $article) {
            $formatted_articles[$article->getId()] = $article;
        }
        return $formatted_articles;
    }
}
