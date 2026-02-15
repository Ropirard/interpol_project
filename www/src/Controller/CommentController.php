<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;
use App\Repository\ArticleRepository; 
use App\Entity\Comment; 
use App\Form\CommentType; 
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class CommentController extends AbstractController
{
#[Route('/article/{id}/comment', name: 'app_comment_create', methods: ['POST'])]
  #[IsGranted('ROLE_USER')]
  public function create(int $id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager, Request $request) 
  {
    // On récupérer l'article actif
    $article = $articleRepository->find($id);

    // Récupérer l'utilisateur actuellement connecté
    $user = $this->getUser();

    // Créer une nouvelle instance d'entité Comment
    $comment = new Comment();

    // Préremplir les relations (user et article)
    // Cela évite des requêtes SQL supplémentaires
    $comment->setUser($user);
    $comment->setArticle($article);

    // On capte les datats du form 
    $commentData = $request->request->all('comment');

    //On vérifie que le token CSRF est présent dans les données soumises car formulaire non traité par symfony (formulaire de réponse) 
    $submittedToken = $commentData['_token'] ?? null;

    // ID du commentaire parent (pour les réponses)
    // null ou '' = commentaire principal
    // numérique = réponse à ce commentaire
    $parentId = $commentData['parentComment'] ?? null;

    // Contenu du commentaire soumis
    $content = $commentData['content'] ?? null;


    // Vérifier si c'est une réponse (parentId non vide et numérique)
    $isReplyForm = $parentId && $parentId !== '' && is_numeric($parentId);

    if ($isReplyForm) {

    //Validation manuelle car FORM HTML
      // Validation du token CSRF 
      if (!$submittedToken || !$this->isCsrfTokenValid('submit', $submittedToken)) {
        $this->addFlash('error', "Le token CSRF est invalide, veuillez réessayer.");
        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
      }

      // Validation du contenu : ne pas vide
      if (empty(trim($content))) {
        $this->addFlash('error', "Le commentaire ne peut pas être vide.");
        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
      }

      // Validation de la taille : max 5000 caractères
      if (strlen($content) > 5000) {
        $this->addFlash('error', "Le commentaire ne peut pas dépasser 5000 caractères.");
        return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
      }

      // Remplir le commentaire avec les données validées
      $comment->setContent($content);
      $comment->setCreatedAt(new DateTime());

      // Trouver le commentaire parent dans la base
      $parentComment = $entityManager->getRepository(Comment::class)->find((int)$parentId);

      // Vérifier que le parent existe ET appartient au même article (sécurité : empêcher de relier à un parent d'un autre article)
      if ($parentComment && $parentComment->getArticle() === $article) {
        $comment->setParentComment($parentComment);
      }

      //On persist en bddle commentaire
      $entityManager->persist($comment);

      // On flush pour enregistrer en bdd
      $entityManager->flush();

      //redirect avec un message de succès
      $this->addFlash('success', "Le commentaire a été ajouté avec succès.");
      return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
    }

    // On capte les datas du formulaire Symfony
    $form = $this->createForm(CommentType::class, $comment);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      //Si le formulaire et valide on set ses données
      $comment->setCreatedAt(new \DateTime());

      $entityManager->persist($comment);
      $entityManager->flush();

      $this->addFlash('success', "Le commentaire a été ajouté avec succès.");
      return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
    }

    //Gestion des erreurs
    if ($form->isSubmitted() && !$form->isValid()) {
      // Récupérer toutes les erreurs d'une traite
      $errors = [];

      // getErrors(deep, flatten) :
      // - deep=true : erreurs du formulaire ET des champs
      // - flatten=false : ne pas aplatir la structure
      foreach ($form->getErrors(true, false) as $error) {
        $errors[] = $error->getMessage();
      }

      // Erreurs de champs individuels
      foreach ($form->all() as $child) {
        foreach ($child->getErrors() as $error) {
          $errors[] = $error->getMessage();
        }
      }

      // Message par défaut si aucune erreur n'a été trouvée
      if (empty($errors)) {
        $errors[] = "Le formulaire contient des erreurs. Veuillez vérifier les données.";
      }

      $this->addFlash('error', "Erreur lors de l'ajout d'un commentaire:" . implode(', ', $errors));
    }
    return $this->redirectToRoute('app_article_show', ['id' => $article->getId()]);
  }
}
