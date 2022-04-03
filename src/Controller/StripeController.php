<?php

namespace App\Controller;


use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Product;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    #injection de dependance:je veux que tu rentre dans ma public function en embarquant Entity manager,cart,REFERENCE)
    public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
    {
        $products_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
    //trouver la commande par sa reference
       $commande = $entityManager->getRepository(Commande::class)->findOneByReference($reference);
    //si il n'ya pas de commande un retour d'erreur
       if(!$commande){
            new JsonResponse(['error' => 'order']);
        }
        
        foreach ($commande->getCommandeDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/images/product/".$product_object->getImage()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $commande->getCarrierPrice(),//le prix
                'product_data' => [//le nom de mon produit
                    'name' => $commande->getCarrierName(),//le nom du delivery
                    'images' => [$YOUR_DOMAIN],//le nom de mon image
                ],
            ],
            'quantity' => 1,
        ];
        
        Stripe::setApiKey('sk_test_51KEW8XB7xXakC0tTcxGD1IbgUmkEfCHlcujqs2XTffnLkYodgvgXI2MUdSNDaqtBkEU9GfkvZmyORJQMTqtIlcwR00NrfyrO0H');

        $checkout_session = Session::create([
            
            'payment_method_types' => ['card'],//j'accepte les carte
            'line_items' => [//les different produits
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $commande->setStripeSessionId($checkout_session->id);
        $entityManager->flush();
        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}