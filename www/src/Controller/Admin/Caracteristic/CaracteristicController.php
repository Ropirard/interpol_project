<?php

namespace App\Controller\Admin\Caracteristic;

use App\Repository\GenderRepository;
use App\Repository\EyesColorRepository;
use App\Repository\HairColorRepository;
use App\Repository\SkinColorRepository;
use App\Repository\NationalityRepository;
use App\Repository\SpokenLangageRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/caracteristic')]
#[IsGranted('ROLE_ADMIN')]
class CaracteristicController extends AbstractController
{
    #[Route('/', name: 'app_admin_caracteristic', methods:['GET'])]
    public function index(EyesColorRepository $eyesColor, GenderRepository $gender, HairColorRepository $hairColor, NationalityRepository $nationality, SkinColorRepository $skinColor, SpokenLangageRepository $spokenLangage): Response
    {


        return $this->render('admin/caracteristic/index.html.twig', [
            'eyesColors' => $eyesColor->findAll(),
            'genders' => $gender->findAll(),
            'hairColors' => $hairColor->findAll(),
            'nationalitys' => $nationality->findAll(),
            'spokenLangages' => $spokenLangage->findAll(),
            'skinColors' => $skinColor->findAll()
        ]);
    }
}