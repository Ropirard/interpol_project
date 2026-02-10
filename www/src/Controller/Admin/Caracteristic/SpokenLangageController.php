<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\SpokenLangage;
use App\Form\SpokenLangageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SpokenLangageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/spokenLangage')]
#[IsGranted('ROLE_ADMIN')]
final class SpokenLangageController extends AbstractController
{
    #[Route('', name: 'app_admin_spokenLangage_index', methods: ['GET'])]
    public function index(SpokenLangageRepository $spokenLangageRepository): Response
    {
        return $this->render('admin/caracteristic/spokenLangage/index.html.twig', [
            'spokenLangages' => $spokenLangageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_spokenLangage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $spokenLangage = new SpokenLangage();
        $form = $this->createForm(SpokenLangageType::class, $spokenLangage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($spokenLangage);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_spokenLangage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/spokenLangage/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/{id}/delete', name: 'app_admin_spokenLangage_delete', methods: ['POST'])]
    public function deletespokenLangage(SpokenLangageRepository $spokenLangage, SpokenLangage $thisspokenLangage, Request $request, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_spokenLangage_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_spokenLangage_index');
        }
        */

        $entityManager->remove($thisspokenLangage);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    #[Route('/{id}/edit', name: 'app_spokenLangage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SpokenLangage $spokenLangage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpokenLangageType::class, $spokenLangage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/spokenLangage/edit.html.twig', [
            'spokenLangage' => $spokenLangage,
            'form' => $form,
        ]);
    }
}
