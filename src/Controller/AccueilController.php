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
            /*  $apicall = file_get_contents("http://www.7timer.info/bin/api.pl?lon=113.17&lat=23.09&CIVIL&product=astro&output=json"); */

            $apikey = "a0742c85528ef8c3c99498b98e9e20e7";
            $apicall = "https://api.openweathermap.org/data/2.5/weather?q=$ville,fr&APPID=$apikey&units=metric&lang=fr";
           $resultapi = json_decode(file_get_contents("$apicall"),true);
         $keys = array_keys($resultapi); 
           /* print_r($keys);
           var_dump($resultapi["weather"][0]["main"]); */
            // requête API avec $ville
            $temperature = $resultapi["main"]["temp"]; // temp récup depuis API
            $condiMeteo = $resultapi["weather"][0]["main"]; // conditions météo de l'api

            if($temperature >= 15 && $condiMeteo == 'Clear' || $condiMeteo == 'few clouds' || $condiMeteo == 'scattered clouds')
            {
                $condiMeteo = $resultapi["weather"][0]["description"];
                $resultapi["weather"][0]["main"];
                $location = "ext";
            }
            else
            {
                $condiMeteo = $resultapi["weather"][0]["description"];
                $resultapi["weather"][0]["main"];
                $location = 'int';
            }

            
            $activites = $activiteRepo->findBy(['location' => "$location"],);

            return $this->render('accueil/activites.html.twig', [
                'controller_name' => 'AccueilController',
                'test'            => $activites,
                'ville'           => $ville,
                'meteo'           => $condiMeteo,
                'location'        => $location,
                'apicall'          => $apicall,
                'resultapi'        => $resultapi

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
            'meteo'           => $meteo,
        ]);
    }
}
