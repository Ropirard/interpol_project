<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil')]
#[IsGranted('ROLE_USER')]
final class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil_show', methods: ['GET'])]
    public function show(): Response
    {
        //On récupère l'utilisateur en session
        $user = $this->getUser();

        return $this->render('profil/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        //On récupère l'utilisateur
        $user = $this->getUser();

        //On capte le form symfony & on capte les datas saisies avec request
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user->setUpdatedAt(new DateTime());
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre profil a été mise à jour avec succès.');
            return $this->redirectToRoute('app_profil_show');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
