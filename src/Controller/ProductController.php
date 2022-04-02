<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        //je passe une variable dans mon tableau qui s'appelle products qui me permet d'afficher 
            //tous mes produits coté twig
        return $this->render('product/index.html.twig', [
        
        //reccuprer mes produit grace a une requete sql find All  et je fais a l'aide de l'ORM (doctrine)
            'products' => $productRepository->findAll(),
        ]);
    }

    
    #[Route('/{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        //methode render est la methode de abstract controller  qui nous permet de generer une vu
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    
    
   
}