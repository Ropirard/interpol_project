<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Media;
use App\Entity\People;
use App\Form\PeopleType;
use App\Service\FileUploader;
use App\Repository\PeopleRepository;
use App\Repository\GenderRepository;
use App\Repository\SkinColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/people')]
final class PeopleController extends AbstractController
{
    private function normalizeType(string $type): string
    {
        return $type === 'criminel' ? 'Criminel' : 'Disparu';
    }

    #[Route('/{type}', name: 'app_people_index', requirements: ['type' => 'criminel|disparu'], methods: ['GET'])]
    public function index(
        string $type,
        PeopleRepository $peopleRepository,
        GenderRepository $genderRepository,
        SkinColorRepository $skinColorRepository,
        Request $request
    ): Response {
        $normalizedType = $this->normalizeType($type);
        $sortBy = $request->query->get('sort', 'recent');
        $filters = $request->query->all();
        unset($filters['sort']);
        
        // Ajouter le filtre de type
        $filters['type'] = $normalizedType;

        $peoples = $peopleRepository->findAllWithFilters($filters, $sortBy);

        $templateData = [
            'peoples' => $peoples,
            'selectSort' => $sortBy,
            'type' => $type,
        ];

        // Ajouter les filtres additionnels pour les criminels
        if ($type === 'criminel') {
            $templateData['genders'] = $genderRepository->findBy([], ['label' => 'ASC']);
            $templateData['selectedGender'] = $request->query->get('gender');
            $templateData['skinColors'] = $skinColorRepository->findBy([], ['label' => 'ASC']);
            $templateData['selectedSkinColor'] = $request->query->get('skinColor');
        }

        return $this->render('people/index.html.twig', $templateData);
    }

    #[Route('/{type}/{id}', name: 'app_people_show', requirements: ['type' => 'criminel|disparu', 'id' => '\d+'], methods: ['GET'])]
    public function show(string $type, int $id, PeopleRepository $peopleRepository): Response
    {
        $people = $peopleRepository->findActive($id);

        if (!$people) {
            $this->addFlash('error', "Ce profil n'existe pas.");
            return $this->redirectToRoute('app_people_index', ['type' => $type], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/show.html.twig', [
            'people' => $people,
            'type' => $type,
        ]);
    }

    #[Route('/{type}/{id}/edit', name: 'app_people_edit', requirements: ['type' => 'criminel|disparu', 'id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(
        string $type,
        Request $request,
        People $people,
        EntityManagerInterface $entityManager,
        FileUploader $fileUploader
    ): Response {
        // Vérifier que c'est un admin qui modifie la personne
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'avez pas la permission de modifier ce profil");
            return $this->redirectToRoute(
                'app_people_show',
                ['type' => $type, 'id' => $people->getId()],
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
                        $this->addFlash('error', "Erreur lors de l'upload d'un fichier : " . $e->getMessage());
                    }
                }
                $entityManager->persist($people);
            }

            $entityManager->flush();
            $this->addFlash('success', "Le profil a été modifié avec succès");
            return $this->redirectToRoute('app_people_index', ['type' => $type], Response::HTTP_SEE_OTHER);
        }

        return $this->render('people/edit.html.twig', [
            'people' => $people,
            'form' => $form,
            'type' => $type,
        ]);
    }

    #[Route('/{id}', name: 'app_people_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, People $people, EntityManagerInterface $entityManager): Response
    {
        $referer = (string) $request->headers->get('referer');
        $isAdminPeople = str_contains($referer, '/admin/people');
        $isMissingPeople = str_contains($referer, '/people/disparu');
        $type = $isMissingPeople ? 'disparu' : 'criminel';

        // Vérifier que l'utilisateur est bien autorisé
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'avez pas l'autorisation de supprimer ce profil.");
            return $this->redirectToRoute(
                $isAdminPeople ? 'app_admin_people_show' : 'app_people_show',
                $isAdminPeople ? ['id' => $people->getId()] : ['type' => $type, 'id' => $people->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // Vérifier le token
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_people_' . $people->getId(), $token)) {
            $this->addFlash('error', "Token CSRF invalide.");
            return $this->redirectToRoute(
                $isAdminPeople ? 'app_admin_people_show' : 'app_people_show',
                $isAdminPeople ? ['id' => $people->getId()] : ['type' => $type, 'id' => $people->getId()],
                Response::HTTP_SEE_OTHER
            );
        }

        // Soft delete
        $people->setIsActive(false);
        $people->setUpdatedAt(new DateTime());

        $entityManager->flush();
        $successMessage = $isMissingPeople
            ? "Le disparu a été supprimé avec succès."
            : "Le criminel a été supprimé avec succès.";
        $this->addFlash('success', $successMessage);

        return $this->redirectToRoute(
            $isAdminPeople ? 'app_admin_people' : 'app_people_index',
            $isAdminPeople ? [] : ['type' => $type],
            Response::HTTP_SEE_OTHER
        );
    }
}
