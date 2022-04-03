<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionsController extends AbstractController
{
     //une route qui permet a notre navigateur d'acceder a notre methode
    #[Route('/conditions', name: 'app_conditions')]
    public function index(): Response
    {
        //methode render est la methode de abstract controller  qui nous permet de generer une vu
        return $this->render('conditions/index.html.twig', [
            'controller_name' => 'ConditionsController',
        ]);
    }
}