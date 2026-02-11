<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\SkinColor;
use App\Form\SkinColorType;
use App\Repository\SkinColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/skinColor')]
#[IsGranted('ROLE_ADMIN')]
final class SkinColorController extends AbstractController
{
    #[Route('', name: 'app_admin_skinColor_index', methods: ['GET'])]
    public function index(SkinColorRepository $skinColorRepository): Response
    {
        return $this->render('admin/caracteristic/skinColor/index.html.twig', [
            'skinColors' => $skinColorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_skinColor_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $skinColor = new SkinColor();
        $form = $this->createForm(SkinColorType::class, $skinColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($skinColor);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_skinColor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/skinColor/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_admin_skinColor_delete', methods: ['POST'])]
    public function deleteskinColor(SkinColorRepository $skinColor, SkinColor $thisskinColor, Request $request, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_skinColor_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_skinColor_index');
        }
        */

        $entityManager->remove($thisskinColor);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    #[Route('/{id}/edit', name: 'app_skinColor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SkinColor $skinColor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SkinColorType::class, $skinColor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/skinColor/edit.html.twig', [
            'skinColor' => $skinColor,
            'form' => $form,
        ]);
    }
}
