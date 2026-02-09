<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\PeopleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(UserRepository $userRepository, PeopleRepository $peopleRepository,): Response
    {
        //Tableau de stats global
        $stats = [
            'users' => [
                'total' => $userRepository->count([]),
                'active' => $userRepository->count(['isActive' => true]),
                'admins' => count(array_filter($userRepository->findAll(), fn($u) => in_array('ROLE_ADMIN', $u->getRoles())))
            ],
            'peoples' => [
                'total' => $peopleRepository->count([]),
                'capture' => $peopleRepository->count(['isCaptured' => true]),
            ]
            //TODO: Add report section 
        ];

        //Récupérer les 5 peoples les plus récent
        $recentPeoples = $peopleRepository->findBy(
            [], //aucun filtre de tri
            ['createdAt' => 'DESC'], // paramètre de tri 
            5 //Limite les résultat a 5 
        );

        //Récupère les 5 derniers utilisateur inscrits
        $recentUsers = $userRepository->findBy(
            ['isActive' => true], //aucun filtre de tri
            ['createdAt' => 'DESC'], // paramètre de tri 
            5 //Limite les résultat a 5 
        );

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'recentPeoples' => $recentPeoples,
            'recentUsers' => $recentUsers
        ]);
    }
}
