<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class CommandeCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;//pour manager l'url de redirection une fois qu'on aura terminer notre methide de traitement

  
    public function __construct(EntityManagerInterface $entityManager, CrudUrlGenerator $crudUrlGenerator)//flush ma donnée
    {
        //initialiser mes variables
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }
   
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }
    
    public function configureActions(Actions $actions): Actions
    {
        //ajouter une action custom(ajouter, editer, supprimer)
        
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')->linkToCrudAction('updatePreparation');//faire le lien avec une methode que je vais créer dans ce controller elle s'appel updatereparationP
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')->linkToCrudAction('updateDelivery');
        return $actions
        ->add('detail', $updatePreparation)
        ->add('detail', $updateDelivery)
        ->add('index','detail');
    }
    // pour modifier l'entité j'ai besoin de AdminContext en injection
    public function updatePreparation(AdminContext $context)
    {
        $commande = $context->getEntity()->getInstance();//chercher getEntity grace a context pour avoir la commande que je souhaite modifier
        $commande->setState(2);
        $this->entityManager->flush();
        
        $this->addFlash('notice', "<span style='color:green;'><strong>La commande ".$commande->getReference()." est bien <u>en cours de préparation</u>.</strong></span>");

        $url = $this->crudUrlGenerator->build()// appeler la methode build pour rediriger l'utilisateur vers la vue détails ou commandes
            ->setController(CommandeCrudController::class)// lui faire passer un setcontroller
            ->setAction('index')//lui donner un set acttion ex:je veux que tu aille dans la page index
            ->generateUrl();

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $commande = $context->getEntity()->getInstance();
        $commande->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:orange;'><strong>La commande ".$commande->getReference()." est bien <u>en cours de livraison</u>.</strong></span>");

        $url = $this->crudUrlGenerator->build()
            ->setController(CommandeCrudController::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.fullname', 'Utilisateur'),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total', 'Total produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state')->setChoices([
            'Non payée' => 0,
            'Payée' => 1,
            'Préparation en cours' => 2,
            'Livraison en cours' => 3
            ]),
            ArrayField::new('commandeDetails', 'Produits achetés')->hideOnIndex()
         ];
    }

}