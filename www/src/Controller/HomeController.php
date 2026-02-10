<?php

namespace App\Controller;

use App\Repository\PeopleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PeopleRepository $peopleRepository): Response
    {
        //Récupérer les 5 peoples les plus récent
        $recentCriminals = $peopleRepository->findBy(
            ['type' => 'Criminel'], //aucun filtre de tri
            ['createdAt' => 'DESC'], // paramètre de tri 
            3 //Limite les résultat a 3 
        );
        return $this->render('home/index.html.twig',[
            "recentCriminals" => $recentCriminals
        ]);
    }
}