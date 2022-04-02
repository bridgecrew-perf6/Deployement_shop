<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Form\CommandeType;
use App\Entity\CommandeDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande", name="commande")
     */
     
    #[Route('/commande', name: 'commande')]
    public function index(Cart $cart, Request $request)
    {
        if (!$this->getUser()->getAddresses()->getValues())
       {

           return $this->redirectToRoute('account_address_add');
       }

        $form = $this->createForm(CommandeType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('commande/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);
    }
     /**
     * @Route("/commande/recapitulatif", name="commande_recap")
     */    
    public function add(Cart $cart, Request $request)
    {
        $form = $this->createForm(CommandeType::class, null, [
            'user' => $this->getUser()//utilisateur en cours
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $carriers = $form->get('carriers')->getData();
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getPhone();

            if ($delivery->getCompany()) {
                $delivery_content .= '<br/>'.$delivery->getCompany();
            }

            $delivery_content .= '<br/>'.$delivery->getAddress();
            $delivery_content .= '<br/>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br/>'.$delivery->getCountry();

            // Enregistrer ma  commande()
            $commande = new Commande();
            $reference = $date->format('dmY').'-'.uniqid();
            $commande->setReference($reference);
            $commande->setUser($this->getUser());
            $commande->setCreatedAt($date);
            $commande->setCarrierName($carriers->getName());
            $commande->setCarrierPrice($carriers->getPrice());
            $commande->setDelivery($delivery_content);
            $commande->setState(0);
            //0:non validé

            $this->entityManager->persist($commande);
            // Enregistrer mes produits CommandeDetails()
            foreach ($cart->getFull() as $product) {
                $commandeDetails = new CommandeDetails();
                $commandeDetails->setMyCommande($commande);
                $commandeDetails->setProduct($product['product']->getName());
                $commandeDetails->setQuantity($product['quantity']);
                $commandeDetails->setPrice($product['product']->getPrice());
                $commandeDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($commandeDetails);
            }

            $this->entityManager->flush();

            return $this->render('commande/add.html.twig', [
                'cart' => $cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $commande->getReference()
            ]);
        }
    
        return $this->redirectToRoute('cart');
    }
}