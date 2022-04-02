<?php

namespace App\Controller;
use App\Classe\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    //j'ai besoin de doctrine pour aller chercher les data de mon produits dans la bdd
    private $entityManager;
    
    //nitialiser la fonction constructet je lui inject entityManagerInterface

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-panier", name="cart")
     */
    
        public function index(Cart $cart)//embarquer la class carte dans une variable carte
    {
        return $this->render('cart/index.html.twig', [
            //reccuperer mon panier et l'afficher dans twig
            'cart' => $cart->getFull()
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(Cart $cart, $id)//pour pouvoir exploiter l'ID dans ma fonction j'ai besoin de le passer en paramaetre
    {
        // ID c'est l'idenifiant de mon produit  que je souhaite donner a mon panier
        $cart->add($id);
        //redirection vers le recap du panier quand l'utilisateur clique sur payer
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_my_cart")//supprimer l'ensemble su panier
     */
    public function remove(Cart $cart)
    {
        //j'appel care remove
        $cart->remove();
        //je redirige vers mes produits lorsque le panierest vide
        return $this->redirectToRoute('products');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete_to_cart")
     */
    public function delete(Cart $cart, $id)
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/decrease/{id}", name="decrease_to_cart")
     */
    public function decrease(Cart $cart, $id)
    {
     
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}