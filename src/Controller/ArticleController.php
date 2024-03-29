<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Ressource;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Form\ReponseFormType;
use App\Form\ArticleType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/', name: 'articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/creer', name: 'create_article')]
    public function CreateArticle(Request $req, EntityManagerInterface $entityManager): Response
    {
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '20M');
        $article = new Article();
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $liste_ressources = $form->get('ressources')->getData();
            $article->setUser($this->getUser());
            foreach ($form->get('categories')->getData() as $category) {
                $article->addCategory($category);
            }
            $article->setTitre($form->get('titre')->getData());
            $article->setCorps($form->get('corps')->getData());
            $article->setType($form->get('type')->getData());
            foreach ($liste_ressources as $ressource) {
                $name = pathinfo($ressource->getClientOriginalName());
                $ressource->move($this->getParameter('kernel.project_dir') . '/public/files/', basename($ressource));
                $pathSymfony = $this->getParameter('kernel.project_dir') . '/public/files/' . basename($ressource);
                $slug = 'files/' . $name['filename'] . '-' . rand(100000, 999999) . '-' . rand(100000, 999999) . '-' . rand(100000, 999999) . '.' . $name['extension'];
                rename($pathSymfony, $this->getParameter('kernel.project_dir') . '/public/' . $slug);

                $fichier = new Ressource();
                $fichier->setCreatedAt(new \DateTimeImmutable());
                $fichier->setTitre($ressource->getClientOriginalName());
                $fichier->setNom($slug);
                $fichier->setArticle($article);
                $article->setRessource($fichier);
                $entityManager->persist($fichier);
            }
            $entityManager->persist($article);
            $entityManager->flush();

            $res = $this->redirectToRoute('app_index');
        } else {
            $res = $this->render('article/create.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        return $res;
    }

    #[Route('/deleteArticle/{id}', name: 'delete_article', methods: ['GET'])]
    public function DeleteArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $repoA = $entityManager->getRepository(Article::class);
        $article = $repoA->find($id);
        $entityManager->remove($article->getId());

        return $this->redirectToRoute('home');
    }

    #[Route('/{id}', name: 'show_article')]
    public function show(EntityManagerInterface $entityManager, int $id, CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        $comment = new Commentaire();
        $reponse = new Commentaire();

        $reponse_form = $this->createForm(ReponseFormType::class, $reponse);
        $reponse_form->handleRequest($request);

        $form = $this->createForm(CommentaireFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->tokenStorage->getToken()->getUser());
            $comment->setArticle($article);
            $entityManager->persist($comment);
            $entityManager->flush();

            $article->setNombreVu($article->getNombreVu() - 1);
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('show_article', ['id' => $id]);
        } elseif ($reponse_form->isSubmitted() && $reponse_form->isValid()) {
            $data = $request->request->all()['reponse_form'];
            $reponse->setcontenu($data['contenu']);
            $reponse->setParent($data['parent']);
            $reponse->setUser($this->tokenStorage->getToken()->getUser());
            $reponse->setArticle($article);
            $entityManager->persist($reponse);
            $entityManager->flush();

            $article->setNombreVu($article->getNombreVu() - 1);
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('show_article', ['id' => $id]);
        } else {
            $article->setNombreVu($article->getNombreVu() + 1);
            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentairesParent' => $commentaireRepository->findCommentsParents($article),
            'commentairesEnfant' => $commentaireRepository->findCommentsChilds($article),
            'form' => $form->createView(),
            'reponse_form_token' => $reponse_form->createView()->children['_token']->vars['value'],
        ]);
    }
}
