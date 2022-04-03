<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request)
    {
        #instancier mon formulaire (en l'injectant dans la variable form) et je me sert de this create form qui est une methode avec des parametres a renseigner(la classe de mon formulaire,$form)
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);//permet de générer le traitement de la saisie du formulaire

        if ($form->isSubmitted() && $form->isValid()) {//si le formulaire a été saisie et si les regles de validations sont valide
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais.');
        }
        //methode render est la methode de abstract controller  qui nous permet de generer une vu
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}