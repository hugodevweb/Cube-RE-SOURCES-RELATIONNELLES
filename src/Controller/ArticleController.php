<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\EnAttente;
use App\Entity\Favorie;
use App\Entity\Ressource;
use App\Entity\Commentaire;
use App\Form\CommentaireFormType;
use App\Form\ReponseFormType;
use App\Form\ArticleType;
use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

    // Show all articles
    #[Route('/', name: 'articles')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    // Création d'un article
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
                $article->addCategory($category) || $article->addCategory("aucune Catégorie");
            }
            $article->setTitre($form->get('titre')->getData());
            $article->setCorps($form->get('corps')->getData());
            foreach ($liste_ressources as $ressource) {
                $name = pathinfo($ressource->getClientOriginalName());
                $ressource->move($this->getParameter('kernel.project_dir') . '/public/files/', basename($ressource));
                $pathSymfony = $this->getParameter('kernel.project_dir') . '/public/files/' . basename($ressource);
                $slug = $name['filename'] . '-' . rand(100000, 999999) . '-' . rand(100000, 999999) . '-' . rand(100000, 999999) . '.' . $name['extension'];
                rename($pathSymfony, $this->getParameter('kernel.project_dir') . '/public/files/' . $slug);

                $fichier = new Ressource();
                $fichier->setType($form->get('type')->getData());
                $fichier->setCreatedAt(new \DateTimeImmutable());
                $fichier->setTitre($ressource->getClientOriginalName());
                $fichier->setNom($slug);
                $fichier->setArticle($article);
                $fichier->setUser($this->getUser());
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

    // Delete an article
    #[Route('/deleteArticle/{id}', name: 'delete_article', methods: ['GET'])]
    public function DeleteArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        if ($article) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_index');
    }

    // Visualisation d'un article
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

        $user = $this->getUser();
        // dd($user->getFavories()->toArray());
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentairesParent' => $commentaireRepository->findCommentsParents($article),
            'commentairesEnfant' => $commentaireRepository->findCommentsChilds($article),
            'form' => $form->createView(),
            'reponse_form_token' => $reponse_form->createView()->children['_token']->vars['value'],
        ]);
    }

    // Add an article to favories
    #[Route('/addFavorieArticle/{id}', name: 'add_favorie_article', methods: ['GET'])]
    public function addFavorieArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        $user = $this->getUser();
        if ($article && $user) {
            $favorie = new Favorie();
            $favorie->setArticle($article);
            $favorie->setUser($user);

            $entityManager->persist($favorie);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('show_article', ['id' => $id]);
    }
    
    // Delete an article to favories
    #[Route('/deleteFavorieArticle/{id}', name: 'delete_favorie_article', methods: ['GET'])]
    public function deleteFavorieArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        $user = $this->getUser();
        if ($article && $user) {
            $favorie = $entityManager->getRepository(Favorie::class)->findOneBy(['user' => $user->getId(), 'article' => $article->getId()]);
            if ($favorie) {
                $entityManager->remove($favorie);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('show_article', ['id' => $id]);
    }
    
    // Add an article to en_attente
    #[Route('/addEnAttenteArticle/{id}', name: 'add_en_attente_article', methods: ['GET'])]
    public function addEnAttenteArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        $user = $this->getUser();
        if ($article && $user) {
            $en_attente = new EnAttente();
            $en_attente->setArticle($article);
            $en_attente->setUser($user);

            $entityManager->persist($en_attente);
            $entityManager->flush();
        }
        return $this->redirectToRoute('show_article', ['id' => $id]);
    }
    
    // Delete an article to en_attente
    #[Route('/deleteEnAttenteArticle/{id}', name: 'delete_en_attente_article', methods: ['GET'])]
    public function deleteEnAttenteArticle(int $id, EntityManagerInterface $entityManager): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);
        $user = $this->getUser();
        if ($article && $user) {
            $en_attente = $entityManager->getRepository(EnAttente::class)->findOneBy(['user' => $user->getId(), 'article' => $article->getId()]);
            if ($en_attente) {
                $entityManager->remove($en_attente);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('show_article', ['id' => $id]);
    }

    // Download la ressource
    #[Route('/download/{id}', name: 'download_file', methods: ['GET'])]
    public function download(int $id, EntityManagerInterface $em)
    {
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException('The article does not exist');
        }
        $zip = new \ZipArchive();
        $zipFileName = $this->getParameter('kernel.project_dir') . '/public/zip/' . $article->getTitre() . '.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE) !== true) {
            return $this->json(['message' => 'Cannot create a zip file'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        foreach ($article->getRessources()->toArray() as $ressource) {
            $filePath = $this->getParameter('kernel.project_dir') . '/public/files/' . $ressource->getNom();

            if (!file_exists($filePath)) {
                return $this->json(['message' => "File $ressource->getTitle() does not exist"], Response::HTTP_NOT_FOUND);
            }

            $zip->addFile($filePath, basename($filePath));
        }

        $zip->close();

        $response = new BinaryFileResponse($zipFileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $article->getTitre() . '.zip');

        return $response;
    }
}
