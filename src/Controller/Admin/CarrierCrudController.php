<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CarrierCrudController extends AbstractCrudController
{
     // il doit retourner un FQCN (nom de classe complet) d'une entité Doctrine ORM
    public static function getEntityFqcn(): string
    {
        return Carrier::class;
    }

    //Grace a la fonction ConfiureField j'indique a easy admin qu'elles sont les inputs que je veux afficher 
    //et en quel format.
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            TextareaField::new('description'),
            MoneyField::new('price')->setCurrency('EUR'),
        ];
    }
    
}