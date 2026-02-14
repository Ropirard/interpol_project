<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Admin/CommentController - Modération des commentaires
 * 
 * CONCEPTS CLÉS :
 * - @IsGranted('ROLE_ADMIN') au niveau classe : page complète réservée aux admins
 * - Recherche côté PHP : array_filter pour filtrer les résultats
 * - Soft moderation : pas de suppression immédiate, vérification CSRF d'abord
 */
#[Route('/admin')]
final class CommentController extends AbstractController
{
    /**
     * Affiche la liste des commentaires avec recherche
     * @param CommentRepository Pour récupérer les commentaires
     * @param Request Pour obtenir le paramètre de recherche
     * @return Response Vue de modération
     */
    #[Route('/comment', name: 'app_admin_comment')]
    public function index(CommentRepository $commentRepository, Request $request): Response
    {
        // Vérifier que l'utilisateur a au moins un des rôles requis
        if (!($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MODERATOR'))) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        // On créer un parametre pour une recherche simple
        $search = $request->query->get('search', '');

       //On récupère tout les commentaires du plus récent au plus ancien
        $comments = $commentRepository->findBy([], ['createdAt' => 'DESC']);

        //on filtre les commentaires selon le contenu ou le nom de l'auteur
        if ($search) {
            $comments = array_filter($comments, function ($comment) use ($search) {
                // Chercher dans le contenu du commentaire
                $foundInContent = stripos($comment->getContent(), $search) !== false;

                // Chercher dans le nom de l'auteur
                $foundInName = stripos($comment->getUser()->getName(), $search) !== false;

                // Retourner true si trouvé dans l'un des deux
                return $foundInContent || $foundInName;
            });
        }

        //reindexer le tableau après filtrage   
        $comments = array_values($comments);

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
            'search' => $search  
        ]);
    }

    /**
     * Supprime un commentaire
     * @param Comment L'entité à supprimer (convertie automatiquement)
     * @param Request Pour vérifier le token CSRF
     * @param EntityManagerInterface Pour persister la suppression
     * @return Response Redirection après suppression
     */
    #[Route('/comment/{id}/delete', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function deleteComment(
        Comment $comment,
        Request $request,
        EntityManagerInterface $entityManager
    ) {
        // Vérifier que l'utilisateur a au moins un des rôles requis
        if (!($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_MODERATOR'))) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        //On valide le csrf token 
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('delete_comment_' . $comment->getId(), $token)) {
            $this->addFlash('error', "Token CSRF invalide");
            return $this->redirectToRoute('app_admin_comment');
        }

        // on remove 
        $entityManager->remove($comment);

        // on flush
        $entityManager->flush();

        //on redirect
        $this->addFlash('success', "Commentaire supprimé avec succès");
        return $this->redirectToRoute('app_admin_comment');
    }
}