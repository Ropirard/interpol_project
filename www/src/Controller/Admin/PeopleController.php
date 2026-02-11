<?php

namespace App\Controller\Admin;

use App\Entity\People;
use App\Form\PeopleType;
use App\Form\People1Type;
use App\Repository\PeopleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[Route('/admin/people')]
#[IsGranted('ROLE_ADMIN')]
final class PeopleController extends AbstractController
{
    #[Route(name: 'app_admin_people', methods: ['GET'])]
    public function index(PeopleRepository $peopleRepository, Request $request): Response
    {
        // On récupère les paramètres de recherche ou de tri depuis l'url
        $search = $request->query->get('search', '');
        $filter = $request->query->get('filter', 'all'); // all, active, inactive

        // On récupère tous les challenges triés du + récent au + ancien
        $peoples = $peopleRepository->findBy([], ['createdAt' => 'DESC']);

        // Filtre de tri
        if ($filter === 'active') {
            $peoples = array_filter($peoples, fn($u) => $u->isCaptured());
        } elseif ($filter === 'inactive') {
            $peoples = array_filter($peoples, fn($u) => !$u->isCaptured());
        }

        // Recherche
        if ($search) {
            $peoples = array_filter($peoples, function ($people) use ($search) {
                return stripos($people->getLastname(), $search) !== false
                    || stripos($people->getName(), $search) !== false
                    || stripos($people->getType(), $search) !== false;
            });
        }

        // Séparer criminels et disparus
        $criminels = array_values(array_filter($peoples, fn($p) => $p->getType() === 'Criminel'));
        $disparus = array_values(array_filter($peoples, fn($p) => $p->getType() === 'Disparu'));

        return $this->render('admin/people/index.html.twig', [
            'criminels' => $criminels,
            'disparus' => $disparus,
            'search' => $search,
            'filter' => $filter
        ]);
    }

    #[Route('/new', name: 'app_admin_people_new', methods: ['GET', 'POST'])]
    public function newPeople(Request $request, EntityManagerInterface $entityManager): Response
    {
        $people = new People();
        $people->setIsActive(true);
        $people->setIsCaptured(false);

        $form = $this->createForm(PeopleType::class, $people, [
            'include_is_captured' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $people->setCreatedAt(new \DateTime());
            $people->setUpdatedAt(new \DateTime());

            $entityManager->persist($people);
            $entityManager->flush();

            $this->addFlash('success', "La personne a été créée avec succès");

            return $this->redirectToRoute('app_admin_people', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/people/new.html.twig', [
            'people' => $people,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_people_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(People $people): Response
    {
        return $this->render('admin/people/show.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_people_edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Request $request, People $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeopleType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_people', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/people/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/people/{id}/toggle_active', name: 'app_admin_people_toggle_active', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function peopleToggleActive(
        People $people,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérifier le token CSRF
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('toggle_people_' . $people->getId(), $token)) {
            $this->addFlash('error', "Token CSRF invalide");
            return $this->redirectToRoute('app_admin_people_show', ['id' => $people->getId()]);
        }

        $people->setIsActive(!$people->isActive());
        $entityManager->flush();

        $this->addFlash('success', sprintf(
            "La personne %s a été %s avec succès",
            $people->getLastname(),
            $people->getName(),
            $people->isActive() ? "activé" : "désactivé"
        ));
        return $this->redirectToRoute('app_admin_people_show', ['id' => $people->getId()]);
    }
}
