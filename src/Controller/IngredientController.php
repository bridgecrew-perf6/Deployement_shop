<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    //une route qui permet a notre navigateur d'acceder a notre methode
    #[Route('/', name: 'ingredient_index', methods: ['GET'])]
    #injection de dependance:je veux que tu rentre dans ma puclic function en embarquant IngredientRepository
    public function index(IngredientRepository $ingredientRepository): Response
    {
        
        //je passe une variable dans mon tableau qui s'appelle ingredients qui me permet d'afficher 
        //tous mes ingredients coté twig
        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
        ]);
    }
    #[Route('/{id}', name: 'ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        //methode render est la methode de abstract controller  qui nous permet de generer une vu
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

}