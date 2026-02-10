<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\People;
use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\PeopleRepository;
use App\Repository\UserRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/report')]
#[IsGranted('ROLE_USER')]
final class ReportController extends AbstractController
{
    #[Route('/people/{id}', name: 'app_report_people', methods: ['GET', 'POST'])]
    public function reportPeople(int $id, PeopleRepository $peopleRepository,  Request $request, EntityManagerInterface $entityManager): Response
    {
        //On créer une instance de report
        $report = new Report();
        //On rècupre l'user en session ainsi que le 'criminel' par son id
        $user = $this->getUser();
        $people = $peopleRepository->find($id);

        //On capte les datas du formulaire symfony
        $form = $this->createForm(ReportType::class, $report);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            //On set les valeurs non geré par le form  
            $report->setCreatedAt(new DateTime());
            $report->setUser($user);
            $report->setPeople($people);

            //On enregistre en BDD
            $entityManager->persist($report);
            $entityManager->flush();

            //On redirect
            $this->addFlash('success', "Merci ! Votre signalement a été envoyé. Notre équipe l'examinera rapidement.");
            return $this->redirectToRoute('app_people_index');
        }

        return $this->render('report/index.html.twig', [
            'form' => $form,
            'people' => $people
        ]);
    }
}
