<?php

namespace App\Controller\Admin\Caracteristic;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic/gender')]
#[IsGranted('ROLE_ADMIN')]
final class GenderController extends AbstractController
{
    /**
     * Afficher la liste des genres
     *
     * @param GenderRepository $genderRepository
     * @return Response
     */
    #[Route('', name: 'app_admin_gender_index', methods: ['GET'])]
    public function index(GenderRepository $genderRepository): Response
    {
        return $this->render('admin/caracteristic/gender/index.html.twig', [
            'genders' => $genderRepository->findAll(),
        ]);
    }

    /**
     * Créer un nouveau genre
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/new', name: 'app_gender_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gender = new gender();
        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gender);
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été créée avec succès");
            return $this->redirectToRoute('app_admin_gender_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/gender/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Supprimer un genre
     *
     * @param Gender $thisGender
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}/delete', name: 'app_admin_gender_delete', methods: ['POST'])]
    public function deletegender(Gender $thisGender, EntityManagerInterface $entityManager)
    {
        /*
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_gender_' . $request, $token)) {
            $this->addFlash('error', "Tokent CSRF invalide");
            return $this->redirectToRoute('app_admin_gender_index');
        }
        */

        $entityManager->remove($thisGender);
        $entityManager->flush();

        $this->addFlash('success', "La couleur a été supprimé avec succés");
        return $this->redirectToRoute('app_admin_caracteristic');
    }

    /**
     * Modifier un genre
     *
     * @param Request $request
     * @param Gender $gender
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_gender_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gender $gender, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "La couleur des yeux a été modifiée avec succès");
            return $this->redirectToRoute('app_admin_caracteristic', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/caracteristic/gender/edit.html.twig', [
            'gender' => $gender,
            'form' => $form,
        ]);
    }
}
