<?php

namespace App\Classe;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//Ajouter des produits au panier et pouvoir les supprimer

class Cart
{
    private $session;//declarer une variable session afin de la construire 
    private $entityManager;
    
    //injection de dependance de SessionInterface et lui donner une variable session
    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        // pour que se soit accessible au sein de ma class
        $this->session = $session;
        $this->entityManager = $entityManager;
    }
    
    // function qui me permet d'ajouter un produit a mon panier pour cela j'aurais besoin de ma sesion interface'
 public function add($id)//je reccupere la variable id qui est l'identifiant de produit que j'ai reccuperer dans le cart controller
    {
        //je stock dans la variable cart le panier qui renvoi lui meme un tableau
        $cart = $this->session->get('cart', []);
       
        if (!empty($cart[$id])) {  //si tu as bien dans ton panier un produit déja insérer 
            $cart[$id]++;          // on lui rajoute une quantité (+1)

        } else {
            $cart[$id] = 1;         // sinon on rajoute un produit
        }
        
        //je veux que tu me set une session qui s'appel cart
        $this->session->set('cart', $cart);
    }

    public function get()
    {
        //reccuperer mon panier de ma session cart
        return $this->session->get('cart');
    }

    public function remove()
    {
        //supprimer mon panier de ma session cart
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        //reccuperer le contenu de cart
        $cart = $this->session->get('cart', []);
        
        // cart c'est un tableau, j'ai besoin de faire un unset 
        //(je retire de mon tableau cart  l'ID qui correspod a que je souhaites supprimer )-->
     
        unset($cart[$id]);
        //je set a nouveau cart et le nouveau cart reccupérer apres la suppression de l'id dans ma session cart
        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
         //reccuperer le contenu de cart
        $cart = $this->session->get('cart', []);
        //vérifier si dans la session la quantité de notre produit est > 1 

        if ($cart[$id] > 1) {  // vérifier si ma clé qui correspond a l'ID est > 1
            $cart[$id]--;      // alors retirer une quantité(-1)
        } else {               //sinon
            unset($cart[$id]); //supprimer mon produit
        }
        //retourner la session set cart avec mon nouveau cart que j'ai reconstituer apres les operations
        return $this->session->set('cart', $cart);
    }
    
    // une methode de securisation
    public function getFull()
    {
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                // get full va chercher this entity manager, il va chercher le produit
                // qui a l'id que je lui envoie et qui est present dans mon tableau de session
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                if (!$product_object) {// si ce product object n'existe pas(si quelqu'un essaye de rajouter unproduit avec un id mais que ce produit n'exsite pas)
                    $this->delete($id);//supprimer le produit du panier qui a l'id de mon foreach
                    continue;// sortir de la boucle et tu ne va pas affecter le produit a cartComplete et du coup il ne sera pas retourné
                }

                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

}