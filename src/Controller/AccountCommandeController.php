<?php

namespace App\Controller;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountCommandeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/mes-commandes", name="account_commande")
     */
    public function index()
    {
        $commandes = $this->entityManager->getRepository(Commande::class)->findSuccessCommandes($this->getUser());

        return $this->render('account/commande.html.twig', [
            'commandes' => $commandes
        ]);
    }
    /**
     * @Route("/compte/mes-commandes/{reference}", name="account_commande_show")
     */
    public function show($reference)
    {
        $commande = $this->entityManager->getRepository(Commande::class)->findOneByReference($reference);

        if (!$commande || $commande->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_commande');
        }

        return $this->render('account/commande_show.html.twig', [
            'commande' => $commande
        ]);
    }
}