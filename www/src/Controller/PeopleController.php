<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Media;
use App\Entity\People;
use App\Form\PeopleType;
use App\Service\FileUploader;
use App\Repository\PeopleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/criminals/new', name: 'app_criminal_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $people = new People();
        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($people);
            $entityManager->flush();

            return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/criminals/new.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/criminals/{id}', name: 'app_criminal_show', methods: ['GET'])]
    public function showCriminal(int $id, PeopleRepository $peopleRepository): Response
    {
        $people = $peopleRepository->findActive($id);

        if (!$people) {
            $this->addFlash('error', "Ce profil n'existe pas.");
            return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/criminals/show.html.twig', [
            'people' => $people
        ]);
    }

    #[Route('/missings/{id}', name: 'app_missing_show', methods: ['GET'])]
    public function showMissing(int $id, PeopleRepository $peopleRepository): Response
    {
        $people = $peopleRepository->findActive($id);

        if (!$people) {
            $this->addFlash('error', "Ce profil n'existe pas.");
            return $this->redirectToRoute('app_missing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/missings/show.html.twig', [
            'people' => $people
        ]);
    }

    #[Route('/{id}/edit', name: 'app_criminal_edit', methods: ['GET', 'POST'])]
    public function editCriminal(Request $request, People $people, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // Vérifier que c'est un admin qui modifie la personne
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'avez pas la permission de modifier ce défi");
            return $this->redirectToRoute(
                'app_criminal_show',
                ['id' => $people->getId()],
                Response::HTTP_FORBIDDEN
            );
        }

        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la date de modification
            $people->setUpdatedAt(new DateTime());

            // Gérer l'upload des nouveaux médias
            $files = $form->get('files')->getData();
            if ($files) {
                foreach ($files as $file) {
                    try {
                        $filename = $fileUploader->upload($file, 'peoples');
                        $media = new Media();
                        $media->setPath($filename);

                        $entityManager->persist($media);
                        $people->addMedium($media);
                    } catch (Exception $e) {
                        $this->addFlash('error', "Error lors de l'upload d'un fichier : " . $e->getMessage());
                    }
                }
                $entityManager->persist($people);
            }

            $entityManager->flush();
            $this->addFlash('success', "Votre défi a été modifié avec succès");
            return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/criminals/edit.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_missing_edit', methods: ['GET', 'POST'])]
    public function editMissing(Request $request, People $people, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        // Vérifier que c'est un admin qui modifie la personne
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'avez pas la permission de modifier ce défi");
            return $this->redirectToRoute(
                'app_missing_show',
                ['id' => $people->getId()],
                Response::HTTP_FORBIDDEN
            );
        }

        $form = $this->createForm(PeopleType::class, $people);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Mettre à jour la date de modification
            $people->setUpdatedAt(new DateTime());

            // Gérer l'upload des nouveaux médias
            $files = $form->get('files')->getData();
            if ($files) {
                foreach ($files as $file) {
                    try {
                        $filename = $fileUploader->upload($file, 'peoples');
                        $media = new Media();
                        $media->setPath($filename);

                        $entityManager->persist($media);
                        $people->addMedium($media);
                    } catch (Exception $e) {
                        $this->addFlash('error', "Error lors de l'upload d'un fichier : " . $e->getMessage());
                    }
                }
                $entityManager->persist($people);
            }

            $entityManager->flush();
            $this->addFlash('success', "Votre défi a été modifié avec succès");
            return $this->redirectToRoute('app_missing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/missings/edit.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_people_delete', methods: ['POST'])]
    public function deleteCriminal(Request $request, People $people, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est bien l'auteur du défi
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'avez pas l'autorisation de supprimer ce défi.");
            return $this->redirectToRoute(
                'app_criminal_show',
                ['id' => $people->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // Vérifier le token
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_people_' . $people->getId(), $token)) {
            $this->addFlash('error', "Token CSRF invalide.");
            return $this->redirectToRoute(
                'app_criminal_show',
                ['id' => $people->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // Soft delete
        $people->setIsActive(false);
        $people->setUpdatedAt(new DateTime());

        $entityManager->flush();
        $this->addFlash('success', "Le criminel a été supprimé avec succès.");

        return $this->redirectToRoute('app_criminal_index', [], Response::HTTP_SEE_OTHER);
    }
}
