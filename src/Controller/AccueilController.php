<?php

namespace App\Controller;

use App\Form\VilleType;
use App\Repository\ActiviteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')]
    public function index(
        ActiviteRepository $activiteRepo,
        Request $request
        ): Response
    {
        $form = $this->createForm(VilleType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $ville = $form['ville']->getData();

            // API
            // requête API avec $ville

            $temperature = 12; // temp récup depuis API
            $condiMeteo = 'nuageux'; // conditions météo de l'api

            if($temperature >= 15 && $condiMeteo == 'nuageux')
            {
                $location = "ext";

            }else
            {
                $location = 'int';
            }

            $activites = $activiteRepo->findBy(['location' => "$location"],);

            return $this->render('accueil/activites.html.twig', [
                'controller_name' => 'AccueilController',
                'test'            => $activites,
                'ville'           => $ville,
                'meteo'           => $condiMeteo,
                'location'        => $location
            ]);

        }



        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'form'            => $form->createView(),
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
