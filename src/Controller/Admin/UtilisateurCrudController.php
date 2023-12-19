<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Entity\Role;
use App\Repository\RoleRepository;

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

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = $this->roleRepository->findAll();
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
}
