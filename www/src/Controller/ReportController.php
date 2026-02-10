<?php

namespace App\Controller;

use App\Form\ReportType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/report', name: 'app_report')]
    public function index(Request $request,): Response
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);

        return $this->render('report/index.html.twig', [
            'form' => $form,
        ]);
    }
}
