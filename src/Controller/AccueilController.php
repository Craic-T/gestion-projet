<?php

namespace App\Controller;

use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(ActiviteRepository $activiteRepo ): Response
    {


        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController'
        ]);
    }

    #[Route('/activites', name: 'activites')]
    public function activites(ActiviteRepository $activiteRepo ): Response
    {
        // VALEURS DE TEST
        $test = $activiteRepo->findAll();
        $ville = 'Bordeaux'; 
        $meteo = 'ciel dégagé';

        return $this->render('accueil/activites.html.twig', [
            'controller_name' => 'AccueilController',
            'test'            => $test,
            'ville'           => $ville,
            'meteo'           => $meteo
        ]);
    }
}
