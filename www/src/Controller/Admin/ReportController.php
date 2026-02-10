<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/report')]
#[IsGranted('ROLE_ADMIN')]
final class ReportController extends AbstractController
{
    #[Route('/', name: 'app_admin_report')]
    public function index(ReportRepository $reportRepository): Response
    {
        //On regroupes les stats des signalements 
        $statsByStatus = [
            'pending' => $reportRepository->count(['statut' => 'en cours']),
            'approved' => $reportRepository->count(['statut' => 'approuvé']),
            'rejected' => $reportRepository->count(['statut' => 'rejecté']),
            'closed' => $reportRepository->count(['statut' => 'fermé']),
            'total' => $reportRepository->count([])
        ];

        $reports = $reportRepository->findAllWithUser();

        return $this->render('admin/report/index.html.twig', [
            'reports' => $reports,
            'statsByStatus' => $statsByStatus,
        ]);
    }

    #[Route('/pending', name: 'app_admin_report_pending', methods: ['GET'])]
    public function pending(ReportRepository $reportRepository): Response
    {
        $reports = $reportRepository->findByWithRelations(['statut' => 'en cours']);

        return $this->render('admin/report/pending.html.twig', [
            'reports' => $reports,
            'count' => count($reports)
        ]);
    }

    #[Route('/{id}', name: 'app_admin_report_show', methods: ['GET'])]
    public function show(int $id, ReportRepository $reportRepository, EntityManagerInterface $em): Response
    {
        $report= $reportRepository->find($id);
        return $this->render('admin/report/show.html.twig', [
            'report' => $report,
        ]);
    }
}
