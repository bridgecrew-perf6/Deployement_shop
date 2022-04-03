<?php

namespace App\Controller\Admin;

use App\Entity\Category;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    // il doit retourner un FQCN (nom de classe complet) d'une entité Doctrine ORM
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    //Grace a la fonction ConfiureField j'indique a easy admin qu'elles sont les inputs que je veux afficher 
    //et en quel format.
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('name'),
            //Field::new('imageFile')
            //->setFormType(VichImageType::class),
            ImageField::new('image')
                    
                    ->setBasePath('uploads/images/category')
                    ->setUploadDir('public/uploads/images/category')
                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                    ->setRequired(false),
            
        ];
    }
    
}