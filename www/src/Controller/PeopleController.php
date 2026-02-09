<?php

namespace App\Controller;

use App\Entity\People;
use App\Form\PeopleType;
use App\Repository\PeopleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/people')]
final class PeopleController extends AbstractController
{
    #[Route('/criminals', name: 'app_criminal_index', methods: ['GET'])]
    public function indexCriminals(PeopleRepository $peopleRepository, Request $request): Response
    {
        $sortBy = $request->query->get('sort', 'recent');
        $filters = $request->query->all();
        unset($filters['sort']);

        $peoples = $peopleRepository->findAllWithFilters($filters, $sortBy);

        return $this->render(
            'people/criminals/index.html.twig',
            [
                'peoples' => $peoples,
                'selectSort' => $sortBy
            ]
        );
    }

    #[Route('/missings', name: 'app_missing_index', methods: ['GET'])]
    public function indexMissings(PeopleRepository $peopleRepository, Request $request): Response
    {
        $sortBy = $request->query->get('sort', 'recent');
        $filters = $request->query->all();
        unset($filters['sort']);

        $peoples = $peopleRepository->findAllWithFilters($filters, $sortBy);

        return $this->render(
            'people/missings/index.html.twig',
            [
                'peoples' => $peoples,
                'selectSort' => $sortBy
            ]
        );
    }

    #[Route('/criminals/new', name: 'app_people_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $people = new People();
        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($people);
            $entityManager->flush();

            return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/new.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_people_show', methods: ['GET'])]
    public function show(People $people): Response
    {
        return $this->render('people/show.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_people_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, People $people, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/edit.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_people_delete', methods: ['POST'])]
    public function delete(Request $request, People $people, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $people->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($people);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
    }
}
