<?php

namespace App\Controller;

use App\Entity\Criminal;
use App\Form\CriminalType;
use App\Repository\CriminalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/criminal')]
final class CriminalController extends AbstractController
{
    #[Route(name: 'app_criminal_index', methods: ['GET'])]
    public function index(CriminalRepository $criminalRepository, Request $request): Response
    {
        $sortBy = $request->query->get('sort', 'recent');
        $filters = $request->query->all();
        unset($filters['sort']);

        $criminals = $criminalRepository->findAllWithFilters($filters, $sortBy);

        return $this->render(
            'criminal/index.html.twig',
            [
                'criminals' => $criminals,
                'selectSort' => $sortBy
            ]
        );
    }

    #[Route('/new', name: 'app_criminal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $criminal = new Criminal();
        $form = $this->createForm(CriminalType::class, $criminal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($criminal);
            $entityManager->flush();

            return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('criminal/new.html.twig', [
            'criminal' => $criminal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_criminal_show', methods: ['GET'])]
    public function show(Criminal $criminal): Response
    {
        return $this->render('criminal/show.html.twig', [
            'criminal' => $criminal,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_criminal_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Criminal $criminal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CriminalType::class, $criminal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('criminal/edit.html.twig', [
            'criminal' => $criminal,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_criminal_delete', methods: ['POST'])]
    public function delete(Request $request, Criminal $criminal, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $criminal->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($criminal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
    }
}
