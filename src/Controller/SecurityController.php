<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="app_login")
     */
    #injection de dependance:je veux que tu rentre dans ma puclic function en embarquant authenticationUtils
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //si tu arrive a avoir un utilisateur a se moment tu le return vers la page d'acceuil
        if ($this->getUser()) {
             return $this->redirectToRoute('account');
        }

        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // obtenir le dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}