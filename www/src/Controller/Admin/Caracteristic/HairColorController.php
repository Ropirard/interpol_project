<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\HairColor;
use App\Form\HairColorType;
use App\Repository\HairColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/hairColor')]
#[IsGranted('ROLE_ADMIN')]
final class HairColorController extends AbstractController
{
    /**
     * Afficher la liste des couleurs de cheveux
     */
    #[Route('', name: 'app_admin_hairColor_index', methods: ['GET'])]
    public function index(HairColorRepository $hairColorRepository): Response
    {
        return $this->render('admin/caracteristic/hairColor/index.html.twig', [
            'hairColors' => $hairColorRepository->findAll(),
        ]);
    }

    /**
     * Créer une nouvelle couleur de cheveux
     */
    #[Route('/new', name: 'app_hairColor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hairColor = new HairColor();
        $form = $this->createForm(HairColorType::class, $hairColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($hairColor);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_hairColor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/hairColor/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Supprimer une couleur de cheveux
     */
    #[Route('/{id}/delete', name: 'app_admin_hairColor_delete', methods: ['POST'])]
    public function deletehairColor(HairColorRepository $hairColor, HairColor $thishairColor, Request $request, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_hairColor_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_hairColor_index');
        }
        */

        $entityManager->remove($thishairColor);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    /**
     * Modifier une couleur de cheveux
     */
    #[Route('/{id}/edit', name: 'app_hairColor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HairColor $hairColor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HairColorType::class, $hairColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/hairColor/edit.html.twig', [
            'hairColor' => $hairColor,
            'form' => $form,
        ]);
    }
}
