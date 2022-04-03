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
    //construire l'entity manager interface de symfony pour communiquer avec la bdd
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;//le stocker dans une variable
    }
    /**
     * @Route("/commande", name="commande")
     */
     
    #[Route('/commande', name: 'commande')]
    //injection de dependence de request et cart pour que notre formulaire soit dans la capacité de connaitre ces variable
    public function index(Cart $cart, Request $request)
    {
        #acceder au utilisateur avec getUser
        #si l'utilisateur a une adresse donner sa valeur si il n'a rien le rediriger vers account_address_add
        if (!$this->getUser()->getAddresses()->getValues())
       {

           return $this->redirectToRoute('account_address_add');
       }
        # initialiser ma variable form et lui passer le formulaire CommandeType
        $form = $this->createForm(CommandeType::class, null, [
        //null car la methode create form attend un deuxieme parametre une instance de la classe qui est lié au formulaire et ici nous somme dans un formulaire qui n'est pas liée a une classe
            'user' => $this->getUser()//je passe a mon formulaire l'utilisateur en cour
        ]);

        return $this->render('commande/index.html.twig', [
            #en variable on lui passe le form
            'form' => $form->createView(),//créer la vue
            'cart' => $cart->getFull()//récupérer le panier
        ]);
    }
     /**
     * @Route("/commande/recapitulatif", name="commande_recap")
     */    
    //fonction add car c'est a ce moment qu'on créer notre commande en bdd
    public function add(Cart $cart, Request $request)
    {
        $form = $this->createForm(CommandeType::class, null, [
            'user' => $this->getUser()//utilisateur en cours
        ]);

        //demander a notre formulaire d'ecouter la requette
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();//initialiser dateTime
            $carriers = $form->get('carriers')->getData();//chercher les data de carriers
            $delivery = $form->get('addresses')->getData();//$delivery va chercher addresses
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content .= '<br/>'.$delivery->getPhone();//chercher les informations

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
            $commande->setUser($this->getUser());// j'ai besoin de set 1 user, et je l'ai dans la methode getUser ,et vu que j'ai une relation avec ma class user et commande je peux faire passer des variables
            $commande->setCreatedAt($date);
            $commande->setCarrierName($carriers->getName());
            $commande->setCarrierPrice($carriers->getPrice());
            $commande->setDelivery($delivery_content);//inserer $deliverycontent dans ma commande pour avoir toute les informations
            $commande->setState(0);
            //0:non validé

            $this->entityManager->persist($commande);
            // Enregistrer mes produits CommandeDetails()
            foreach ($cart->getFull() as $product) {//pour chaque produit que j'ai dans mon panier 
                $commandeDetails = new CommandeDetails();
                $commandeDetails->setMyCommande($commande);
                $commandeDetails->setProduct($product['product']->getName());//chercher le nom du produit
                $commandeDetails->setQuantity($product['quantity']);//la quantité du produit
                $commandeDetails->setPrice($product['product']->getPrice());//le prix du produit
                $commandeDetails->setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($commandeDetails);//figer les données
            }

            $this->entityManager->flush();//envoyer les donnees en bdd

            return $this->render('commande/add.html.twig', [
                'cart' => $cart->getFull(),//reccuperer mon panier avec getFull
                'carrier' => $carriers,
                'delivery' => $delivery_content,//on fait passer le deliveery_content que j'ai construit a twig
                'reference' => $commande->getReference()
            ]);
        }
    
        //redirection vers le panier
        return $this->redirectToRoute('cart');
    }
}