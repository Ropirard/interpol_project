<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\Nationality;
use App\Form\NationalityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NationalityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/nationality')]
#[IsGranted('ROLE_ADMIN')]
final class NationalityController extends AbstractController
{
    /**
     * Afficher la liste des nationalités
     */
    #[Route('', name: 'app_admin_nationality_index', methods: ['GET'])]
    public function index(NationalityRepository $nationalityRepository): Response
    {
        return $this->render('admin/caracteristic/nationality/index.html.twig', [
            'nationalitys' => $nationalityRepository->findAll(),
        ]);
    }

    /**
     * Créer une nouvelle nationalité
     */
    #[Route('/new', name: 'app_nationality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nationality = new nationality();
        $form = $this->createForm(NationalityType::class, $nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($nationality);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_nationality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/nationality/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Supprimer une nationalité
     */
    #[Route('/{id}/delete', name: 'app_admin_nationality_delete', methods: ['POST'])]
    public function deletenationality(NationalityRepository $nationality, Nationality $thisnationality, Request $request, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_nationality_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_nationality_index');
        }
        */

        $entityManager->remove($thisnationality);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    /**
     * Modifier une nationalité
     */
    #[Route('/{id}/edit', name: 'app_nationality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nationality $nationality, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NationalityType::class, $nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/nationality/edit.html.twig', [
            'nationality' => $nationality,
            'form' => $form,
        ]);
    }
}
