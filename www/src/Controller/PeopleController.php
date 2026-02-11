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
    #[Route(name: 'app_people_index', methods: ['GET'])]
    public function index(PeopleRepository $peopleRepository): Response
    {
        return $this->render('people/index.html.twig', [
            'people' => $peopleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_people_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new People();
        $form = $this->createForm(PeopleType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_people_show', methods: ['GET'])]
    public function show(People $person): Response
    {
        return $this->render('people/show.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_people_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, People $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeopleType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_people_delete', methods: ['POST'])]
    public function delete(Request $request, People $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_people_index', [], Response::HTTP_SEE_OTHER);
    }
}
