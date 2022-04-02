<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
       $this->entityManager = $entityManager; 
    }
    #[Route('/compte/modification_mot_de_passe', name: 'account_password')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $notification = null;

        $user =$this->getUser();//appelé l'utilisateur connecté et l'injecter dans cette varaibale user 
        //le passer a mon formulaire
        $form =$this->createForm(ChangePasswordType::class, $user);
        #on dit au formulaire qu'il a besoin d'ecouter la requete entrante(manipuler,analyser l'objet requete créer par symfony pour voir si a l'interieur ya pas un post)
        $form->handleRequest($request);
        #si mon formulaire est soumis et valide par apport au contraintes qu'on a renseigner dans RegistrationFormType
        if ($form->isSubmitted() && $form->isValid()) {
            $input_password = $form->get('old_password')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $input_password)){
                $new_password = $form->get('new_password')->getData();
                $password = $userPasswordHasher->hashPassword($user, $new_password);

                $user->setpassword($password);
                #$this->entityManager->persist($user);
                #nul besoin de faire un persist car il s'agit d'une mise à jour de données et non d'une véritable création
                $this->entityManager->flush();
                $notification = "Votre mot de passe a été modifé avec succès.";
            }
            else{
                $notification = "Votre mot de passe actuel n'est pas le bon.";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}