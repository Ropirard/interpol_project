<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\EyesColor;
use App\Form\EyesColorType;
use App\Repository\EyesColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/eyescolor')]
#[IsGranted('ROLE_ADMIN')]
final class EyesColorController extends AbstractController
{
    /**
     * Afficher la liste des couleurs des yeux
     */
    #[Route('', name: 'app_admin_eyescolor_index', methods: ['GET'])]
    public function index(EyesColorRepository $eyesColorRepository): Response
    {
        return $this->render('admin/caracteristic/eyesColor/index.html.twig', [
            'eyesColors' => $eyesColorRepository->findAll(),
        ]);
    }

    /**
     * Créer une nouvelle couleur des yeux
     */
    #[Route('/new', name: 'app_eyescolor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eyesColor = new EyesColor();
        $form = $this->createForm(EyesColorType::class, $eyesColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eyesColor);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_eyescolor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/eyesColor/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Supprimer une couleur des yeux
     */
    #[Route('/{id}/delete', name: 'app_admin_eyescolor_delete', methods: ['POST'])]
    public function deleteEyescolor(EyesColorRepository $eyesColor, EyesColor $thisEyesColor, Request $request, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_eyescolor_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_eyescolor_index');
        }
        */

        $entityManager->remove($thisEyesColor);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    /**
     * Modifier une couleur des yeux
     */
    #[Route('/{id}/edit', name: 'app_eyescolor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EyesColor $eyesColor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EyesColorType::class, $eyesColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/eyesColor/edit.html.twig', [
            'eyesColor' => $eyesColor,
            'form' => $form,
        ]);
    }
}
