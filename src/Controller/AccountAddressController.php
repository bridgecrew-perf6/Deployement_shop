<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    private $entityManager;
    
    //pour envoyer les données en bdd on a besoin de entity manager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/adresses", name="account_address")
     */
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_address_add")
     */
    public function add(Cart $cart, Request $request)
    {
        //j'ai besoin d'un formulaire, de l'ecouter et de l'envoyer ama vue twig
        
        $address = new Address();// créer une instance de la classe adress
        
        //nouvelle variable form qui contient la methode createform et je lui passe le adresseType et d'une instance de la classe adresse
        $form = $this->createForm(AddressType::class, $address);
        
        // gérer la soumission du formulaire(ecouter la requete)
        $form->handleRequest($request);
        
        // si le fourmulaire et soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            //lier l'adresse à l'utilisateur 
            $address->setUser($this->getUser());
            
            //enregistrer l'adresse en bdd (persistet flush)   
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            
            if ($cart->get()) {//si j'ai des produit dans mon panier 
                return $this->redirectToRoute('commande');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }
        //passer dans twig le tableau de variables 
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_address_edit")
     */
    public function edit(Request $request, $id)
    {
        // on va chercher l'adress qu'on veut editer grace a get repository de l'entity address
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        //est ce que mon adresse existe ou je vais chercher le user qui est lié a cette adresse et verifier que c'est bien  l'utilisateur connecté
        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_address_delete")
     */
    public function delete($id)
    {
         // on va chercher l'adress qu'on veut editer grace a get repository de l'entity address
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            // si y'a bien une adresse et que l'adresse correspond bien a l'utilisateur connecté
            $this->entityManager->remove($address);// on fige la donnée
            $this->entityManager->flush();// on envoie la donnée en bdd
        }

        return $this->redirectToRoute('account_address');
    }

}