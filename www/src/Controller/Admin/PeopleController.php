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
        // On récupère les paramètres de recherche/filtre depuis l'url (séparés par catégorie)
        $searchCriminal = $request->query->get('search_criminal', '');
        $filterCriminal = $request->query->get('filter_criminal', 'all'); // all, active, inactive
        $searchMissing = $request->query->get('search_missing', '');
        $filterMissing = $request->query->get('filter_missing', 'all'); // all, active, inactive

        // On récupère tous les people triés du + récent au + ancien
        $peoples = $peopleRepository->findBy([], ['createdAt' => 'DESC']);

        // Séparer les criminels et les disparus
        $criminals = array_filter($peoples, fn($p) => $p->getType() === 'Criminel');
        $missings = array_filter($peoples, fn($p) => $p->getType() === 'Disparu');

        // Filtre + recherche (Criminels)
        if ($filterCriminal === 'active') {
            $criminals = array_filter($criminals, fn($u) => $u->isActive());
        } elseif ($filterCriminal === 'inactive') {
            $criminals = array_filter($criminals, fn($u) => !$u->isActive());
        }

        if ($searchCriminal) {
            $criminals = array_filter($criminals, function ($people) use ($searchCriminal) {
                return stripos($people->getLastname(), $searchCriminal) !== false
                    || stripos($people->getName(), $searchCriminal) !== false
                    || stripos($people->getType(), $searchCriminal) !== false;
            });
        }

        // Filtre + recherche (Disparus)
        if ($filterMissing === 'active') {
            $missings = array_filter($missings, fn($u) => $u->isActive());
        } elseif ($filterMissing === 'inactive') {
            $missings = array_filter($missings, fn($u) => !$u->isActive());
        }

        if ($searchMissing) {
            $missings = array_filter($missings, function ($people) use ($searchMissing) {
                return stripos($people->getLastname(), $searchMissing) !== false
                    || stripos($people->getName(), $searchMissing) !== false
                    || stripos($people->getType(), $searchMissing) !== false;
            });
        }

        return $this->render('admin/people/index.html.twig', [
            'criminals' => array_values($criminals),
            'missings' => array_values($missings),
            'searchCriminal' => $searchCriminal,
            'filterCriminal' => $filterCriminal,
            'searchMissing' => $searchMissing,
            'filterMissing' => $filterMissing
        ]);
    }

    #[Route('/{id}', name: 'app_admin_people_show', methods: ['GET'])]
    public function show(People $people): Response
    {
        return $this->render('admin/people/show.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_people_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, People $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PeopleType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_people_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/people/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/people/{id}/toggle_active', name: 'app_admin_people_toggle_active', methods: ['POST'])]
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
