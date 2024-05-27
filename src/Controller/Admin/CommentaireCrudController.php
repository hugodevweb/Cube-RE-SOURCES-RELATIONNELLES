<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CommentaireRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class CommentaireCrudController extends AbstractCrudController
{
    private $articleRepository;
    private $commentaireRepository;

    public function __construct(ArticleRepository $articleRepository, CommentaireRepository $commentaireRepository, UserRepository $utilisateurRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->commentaireRepository = $commentaireRepository;
        $this->utilisateurRepository = $utilisateurRepository;
    }
  
    public static function getEntityFqcn(): string
    {
        return Commentaire::class;
    }
  
    public function configureFields(string $pageName): iterable
    {
        $commentaires_parents = $this->formatParentForChoices($this->commentaireRepository->findall());
        // dd($commentaires_parents);

        $res =  [
            IdField::new('id')->onlyOnIndex(),
            TextEditorField::new('contenu'),
            ChoiceField::new('user')->setChoices($this->formatUserForChoices($this->utilisateurRepository->findAll()))->onlyOnForms(),
            TextField::new('user')->onlyOnIndex(),
            ChoiceField::new('article')
                ->setChoices($this->formatArticleForChoices($this->articleRepository->findAll()))
                ->onlyOnForms(),
            AssociationField::new('article')
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->autocomplete()
                ->setCustomOptions([
                    'widget' => 'native',
                ])
                ->setRequired(true)
                ->onlyOnIndex(),
            IdField::new('parent')->onlyOnIndex(),
        ];
        if (!empty($commentaires_parents)) {
            array_push($res, 
                ChoiceField::new('parent')->setChoices($this->formatParentForChoices($this->commentaireRepository->findall()))->onlyOnForms());
        }
        array_push($res, BooleanField::new('est_actif'));
        return $res;
    }

    private function formatArticleForChoices(array $articles): array
    {
        $formattedArticle = [];
        foreach ($articles as $article) {
            $formattedArticle[$article->getTitre()] = $article;
        }
        return $formattedArticle;
    }

    public function formatParentForChoices(array $comments)
    {
        $choices = [];
        foreach ($comments as $comment) {
            $choices[$comment->getId() . ' - ' . strip_tags(substr($comment->getContenu(),0,50)) . '...'] = $comment->getId();
        }
        return $choices;
    }

    public function formatUserForChoices(array $users)
    {
        $choices = [];
        foreach ($users as $user) {
            $choices[$user->getPseudo() . ' - ' . $user->getNom() . ' ' . $user->getPrenom()] = $user;
        }
        return $choices;
    }
}
