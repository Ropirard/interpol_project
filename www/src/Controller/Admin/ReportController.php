<?php

namespace App\Controller\Admin;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/report')]
#[IsGranted('ROLE_ADMIN')]
final class ReportController extends AbstractController
{

    /**
     * Montre tout les signalement 
     * @param ReportRepository $reportRepository 
     * @return Response  
     */
    #[Route('/', name: 'app_admin_report')]
    public function index(ReportRepository $reportRepository): Response
    {
        //On regroupes les stats des signalements pour classer les reports
        $statsByStatus = [
            'pending' => $reportRepository->count(['statut' => 'en cours']),
            'approved' => $reportRepository->count(['statut' => 'approuvé']),
            'rejected' => $reportRepository->count(['statut' => 'rejecté']),
            'closed' => $reportRepository->count(['statut' => 'fermé']),
            'total' => $reportRepository->count([])
        ];

        //On récupère tout les derniers signalements peut importe leurs statut 
        $reports = $reportRepository->findAll();

        return $this->render('admin/report/index.html.twig', [
            'reports' => $reports,
            'statsByStatus' => $statsByStatus,
        ]);
    }

    /**
     * Montre tout les signalement 'en cours' de traitement
     * @param ReportRepository $reportRepository 
     * @return Response  
     */
    #[Route('/pending', name: 'app_admin_report_pending', methods: ['GET'])]
    public function pending(ReportRepository $reportRepository): Response
    {
        //On récupère tout les signalement qui sont 'en cours'
        $reports = $reportRepository->findBy(['statut' => 'en cours']);

        return $this->render('admin/report/pending.html.twig', [
            'reports' => $reports,
            'count' => count($reports)
        ]);
    }

    /**
     * Detail d'un signalement 
     * @param ReportRepository $reportRepository 
     * @return Response  
     */
    #[Route('/{id}', name: 'app_admin_report_show', methods: ['GET'])]
    public function show(int $id, ReportRepository $reportRepository): Response
    {
        //On récupère LE signalement avec son id 
        $report = $reportRepository->find($id);

        return $this->render('admin/report/show.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * Approuver un signalement 
     * @param Report $report Le signalement 
     * @param EntityManagerInterface $em P
     * @param Request $request 
     * @return Response Redrection 
     */
    #[Route('/{id}/approve', name: 'app_admin_report_approve', methods: ['POST'])]
    public function approve(Report $report, EntityManagerInterface $em, Request $request): Response
    {
        //Form non traité par symfony donc verif manuelle du csrftoken
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('approve' . $report->getId(), $token)) {
            $this->addFlash('error', "Token CSRF Invalide");
            return $this->redirectToRoute('app_admin_report');
            Response::HTTP_FORBIDDEN;
        }

        //On met a jour le statut 
        $report->setStatut('approuvé');
        $report->setResolvedAt(new \DateTime());

        //On enregistre en bdd
        $em->flush();

        //on redirect
        $this->addFlash('success', 'Le signalement a été approuvé.');
        return $this->redirectToRoute('app_admin_report');
    }

    /**
     * Rejeter un signalement 
     * @param Report $report Le signalement 
     * @param EntityManagerInterface $em 
     * @param Request $request 
     * @return Response 
     */
    #[Route('/{id}/reject', name: 'app_admin_report_reject', methods: ['POST'])]
    public function reject(Report $report, EntityManagerInterface $em, Request $request): Response
    {
        //Form non traité par symfony donc verif manuelle du csrftoken
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('reject' . $report->getId(), $token)) {
            $this->addFlash('error', "Token CSRF Invalide");
            return $this->redirectToRoute('app_admin_report');
            Response::HTTP_FORBIDDEN;
        }

        //On met a jour le statut 
        $report->setStatut('rejecté');
        $report->setResolvedAt(new \DateTime());

        //On enregistre en bdd
        $em->flush();

        //on redirect
        $this->addFlash('success', 'Le signalement a été rejecté.');
        return $this->redirectToRoute('app_admin_report');
    }

    /**
     * Marquer un signalement comme fermé 
     * @param Report $report Le signalement
     * @param EntityManagerInterface $em EntityManager
     * @param Request $request 
     * @return Response 
     */
    #[Route('/{id}/close', name: 'app_admin_report_close', methods: ['POST'])]
    public function close(Report $report, EntityManagerInterface $em, Request $request): Response
    {
        //Form non traité par symfony donc verif manuelle du csrftoken
        $token = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('close' . $report->getId(), $token)) {
            $this->addFlash('error', "Token CSRF Invalide");
            return $this->redirectToRoute('app_admin_report');
            Response::HTTP_FORBIDDEN;
        }

        //On met a jour le statut 
        $report->setStatut('fermé');
        $report->setResolvedAt(new \DateTime());

        //On enregistre en bdd
        $em->flush();

        //on redirect
        $this->addFlash('success', 'Le signalement a été fermé.');
        return $this->redirectToRoute('app_admin_report');
    }
}
