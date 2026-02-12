<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Media;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/article')]
#[IsGranted('ROLE_ADMIN')]
final class ArticleController extends AbstractController
{

    /**
     * Montre tout les articles 
     * @param ArticleRepository $articleRepository 
     * @param Request $request 
     * @return Response  
     */
    #[Route(name: 'app_admin_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        // on recupère les parametre de recherche ou de tri depuis l'url
        $search = $request->query->get('search', '');
        $filter = $request->query->get('filter', 'all');

        //On retourne la liste de tous les articles triés du plus recent au plus ancien
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        // Filtre de tri
        if ($filter === 'publie') {
            $articles = array_filter($articles, fn($u) => $u->isPublished());
        } elseif ($filter === 'archive') {
            $articles = array_filter($articles, fn($u) => !$u->isPublished());
        }

        // Recherche selon le titre ou le contenu de l'article
        if ($search) {
            $articles = array_filter($articles, function ($article) use ($search) {
                return stripos($article->getTitle(), $search) !== false
                    || stripos($article->getContent(), $search) !== false;
            });
        }

        //reindexer le tableau après filtrage
        $articles = array_values($articles);

        //On regroupes les stats des articles pour classer les articles
        $statsByStatus = [
            'publie' => $articleRepository->count(['isPublished' => true]),
            'archive' => $articleRepository->count(['isPublished' => false]),
            'total' => $articleRepository->count([])
        ];

        return $this->render('admin/article/index.html.twig', [
            'articles' => $articles,
            'statsByStatus' => $statsByStatus,
            'search' => $search,
            'filter' => $filter,
        ]);
    }

    /**
     * Creer un article  
     * @param EntityManagerInterface $entityManager
     * @param Request $request 
     * @param FileUploader $fileUploader
     * @return Response  
     */
    #[Route('/new', name: 'app_admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // On crée une nouvelle instance d'article
        $article = new Article();

        //On capte les datas du formulaire symfony
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on set les données de l'article
            $article->setCreatedAt(new DateTime());
            $article->setIsActive(true);
            $article->setIsPublished(true);

            // On persiste l'article en base de données pour avoir son ID pour le media
            $entityManager->persist($article);

            $files = $form->get('files')->getData();
            if ($files) {
                foreach ($files as $file) {
                    try {
                        //upload du fichier 
                        $filename = $fileUploader->upload($file, 'articles');

                        //on enregistre en bdd les médias
                        $media = new Media();
                        $media->setPath($filename);

                        $entityManager->persist($media);
                        $article->addMedium($media);
                    } catch (Exception $e) {
                        $this->addFlash('error', "Erreur lors de l'upload d'un fichier :" . $e->getMessage());
                    }
                }
            }

            $entityManager->flush();
            $this->addFlash('success', "Article créé avec succès.");
            return $this->redirectToRoute('app_admin_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * Modifier un article
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @param FileUploader $fileUploader
     * @param Request $request 
     * @return Response  
     */
    #[Route('/{id}/edit', name: 'app_admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        //On capte les datas du formulaire symfony
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //on set les données de l'article
            $article->setUpdatedAt(new DateTime());

            // On persiste l'article en base de données pour avoir son ID pour le media
            $entityManager->persist($article);

            // On gère les fichiers uploadés
            $files = $form->get('files')->getData();
            if ($files) {
                foreach ($files as $file) {
                    try {
                        //upload du fichier 
                        $filename = $fileUploader->upload($file, 'articles');

                        //on enregistre en bdd les médias
                        $media = new Media();
                        $media->setPath($filename);

                        $entityManager->persist($media);
                        $article->addMedium($media);
                    } catch (Exception $e) {
                        $this->addFlash('error', "Erreur lors de l'upload d'un fichier :" . $e->getMessage());
                    }
                }

                $entityManager->persist($article);
            }

            $entityManager->flush();
            $this->addFlash('success', "Article modifié avec succès.");
            return $this->redirectToRoute('app_admin_article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    /**
     * Supprimer un article
     * @param Article $article
     * @param EntityManagerInterface $entityManager
     * @param Request $request 
     * @return Response  
     */
    #[Route('/{id}/delete', name: 'app_admin_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        //Vérifier le Token 
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_article_' . $article->getId(), $token)) {
            $this->addFlash('error', "Token CSRF Invalide");
            return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
            Response::HTTP_FORBIDDEN;
        }

        //Soft delete (flag isPublished a désactiver, pas une vrai suppression car il y aura des commentaires liés à l'article on l'archive juste)
        $article->setIsPublished(false);
        $article->setUpdatedAt(new DateTime());

        $entityManager->flush();
        $this->addFlash('success', "Votre article a bien été supprimé");
        return $this->redirectToRoute('app_admin_article', [], Response::HTTP_SEE_OTHER);
    }
}
