<?php

namespace App\Controller;

use App\Form\CommentType;
use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\ArticleRepository;
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

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request): Response
    {
        // ========== PRÉPARATION DU FORMULAIRE DE COMMENTAIRE ==========

        // Formulaire pour ajouter un NOUVEAU commentaire (principal, pas réponse)
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        // ========== RÉCUPÉRATION DES COMMENTAIRES ==========

        /**
         * Filtrer les commentaires : prendre seulement les PRINCIPAUX
         * (ceux sans parent, les réponses ont un parentComment)
         * 
         * filter() : applique un callback et ne retient que les vrais
         * toArray() : convertit de Collection à array PHP
         */
        $comments = $article->getComments()->filter(function (Comment $comment) {
            return $comment->getParentComment() === null;
        })->toArray();

        /**
         * TRI DES COMMENTAIRES
         * 
         * usort() : trie un array avec une fonction de comparaison personnalisée
         * <=> : opérateur de comparaison spaceship
         * - Retourne -1 si $a < $b
         * - Retourne 0 si $a == $b
         * - Retourne 1 si $a > $b
         * 
         * $b->getCreatedAt() <=> $a->getCreatedAt() :
         * - Compare les dates en ordre INVERSE (les récents EN PREMIER)
         */
        usort($comments, function (Comment $a, Comment $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });
        
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm,
            'comments' => $comments
        ]);
    }
}
