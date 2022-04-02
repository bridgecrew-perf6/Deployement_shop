<?php

namespace App\Controller;
use App\Classe\Cart;
use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class CommandeSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="commande_success")
     */
    public function index(Cart $cart, $stripeSessionId)
    {
        $commande = $this->entityManager->getRepository(Commande::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$commande || $commande->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($commande->getState() == 1) {
            
            $cart->remove();
            $commande->setState(1);
            $this->entityManager->flush();
        }
             //Envoyer un email à notre client pour lui confirmer sa commande
            //$mail = new Mail();
            //$content = "Bonjour ".$commande->getUser()->getFirstname()."<br/>Merci pour votre commande.<br><br/>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam expedita fugiat ipsa magnam mollitia optio voluptas! Alias, aliquid dicta ducimus exercitationem facilis, incidunt magni, minus natus nihil odio quos sunt?";
            //$mail->send($commande->getUser()->getEmail(), $commande->getUser()->getFirstname(), 'Votre commande La Boutique Française est bien validée.', $content);
            //}
        
        return $this->render('commande_success/index.html.twig', [
            'commande' => $commande
        ]);
    }
}