<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Entity\Commentaire;
use App\Entity\Evenement;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cube RE SOURCES RELATIONNELLES');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Site');
        yield MenuItem::linkToCrud('Article', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comments', Commentaire::class);
        yield MenuItem::linkToCrud('Categorie', 'fas fa-list', Categorie::class);

        yield MenuItem::section('');
        yield MenuItem::linkToCrud('Événement', 'fas fa-calendar-days', Evenement::class);

        yield MenuItem::section('Administration');
        yield MenuItem::linkToCrud('Utilisateur', 'fa-solid fa-user', Utilisateur::class);
        yield MenuItem::linkToCrud('Role', 'fa-solid fa-users', Role::class);
    }
}
