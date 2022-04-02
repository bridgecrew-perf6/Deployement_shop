<?php

namespace App\Controller;

use App\Entity\Commande;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/erreur/{stripeSessionId}", name="commande_cancel")
     */
    public function index($stripeSessionId)
    {
        $commande = $this->entityManager->getRepository(Commande::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$commande || $commande->getUser() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Envoyer un email à notre utilisateur pour lui indiquer l'échec de paiement

        return $this->render('commande_cancel/index.html.twig', [
            'commande' => $commande
        ]);
    }
}