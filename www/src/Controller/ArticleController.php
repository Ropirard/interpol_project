<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Entity\Article; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    /**
     * Affiche la page d'accueil des articles
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    #[Route('/', name: 'app_article')]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        // on recupère les parametre de recherche ou de tri depuis l'url
        $search = $request->query->get('search', '');

        //On retourne la liste de tous les articles triés du plus recent au plus ancien
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);

        // Recherche selon le titre ou le contenu de l'article
        if ($search) {
            $articles = array_filter($articles, function ($article) use ($search) {
                return stripos($article->getTitle(), $search) !== false
                    || stripos($article->getContent(), $search) !== false;
            });
        }

        //reindexer le tableau après filtrage
        $articles = array_values($articles);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'search' => $search,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
