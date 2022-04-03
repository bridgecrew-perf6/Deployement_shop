<?php

namespace App\Controller\Admin;

use App\Entity\Ingredient;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IngredientCrudController extends AbstractCrudController
{
    // il doit retourner un FQCN (nom de classe complet) d'une entité Doctrine ORM
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
    }

    //Grace a la fonction ConfiureField j'indique a easy admin qu'elles sont les inputs que je veux afficher 
    //et en quel format.
    public function configureFields(string $pageName): iterable
    {
        return [
           
            TextField::new('name'),
            TextareaField::new('description'),
            //Field::new('imageFile')
            //->setFormType(VichImageType::class),
            ImageField::new('image')
            ->setUploadDir('public/uploads/images/ingredient')

            ->setBasePath('uploads/images/ingredient')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
            

        ];
    }
    
}