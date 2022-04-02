<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'register')]
    #injection de dependance:je veux que tu rentre dans ma puclic function en embarquant l'object request, userPasswordHasher, entitymanager et Mailerinterface
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        #instancier ma class user
        $user = new User();
        
        #instancier mon formulaire (en l'injectant dans la variable form) et je me sert de this create form qui est une methode avec des parametres a renseigner(la classe de mon formulaire,$user)
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        #on dit au formulaire qu'il a besoin d'ecouter la requete entrante(manipuler,analyser l'objet requete créer par symfony pour voir si a l'interieur ya pas un post)
        $form->handleRequest($request);//on passe a notre formulaire l'object request avec la methode handleRequest
        
        #si mon formulaire est soumis et valide par apport au contraintes qu'on a renseigner dans RegistrationFormType
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,//on inject a user toutes les données du password qu'on reccupere du formlaire
                    $form->get('password')->getData()
                )
            );
            #Enregistrer les données dans ma base de données(on appel doctrine) on se sert de l'entity manager pour faire nos manipulations(la convention de symfony l'appel entity manager)
            $entityManager->persist($user);//figer la data car j'ai besoin de l'enregistrer
            $entityManager->flush();// exécuter la data enregistré dans la base de données 
            // do anything else you need here, like send an email
            
            $email = (new TemplatedEmail())
                ->from($user->getEmail())
                ->to('contact.haircaire@gmail.com')
                ->subject('Merci pour votre inscription!')
                ->htmlTemplate('emails/register.html.twig')
                ->context([
                    'user' => $user
                ]);
                
            $mailer->send($email);
                
            return $this->redirectToRoute('home');
        }
        #passser la variable form au template avec create view
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}