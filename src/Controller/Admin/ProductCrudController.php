<?php

namespace App\Controller\Admin;

use App\Entity\Product;

use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    // il doit retourner un FQCN (nom de classe complet) d'une entité Doctrine ORM
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    //Grace a la fonction ConfiureField j'indique a easy admin qu'elles sont les inputs que je veux afficher 
    //et en quel format.
    public function configureFields(string $pageName): iterable
    {
        return [
            //je veux afficher un textField qui est bien dans easy admin bundle une nouvelle propriete nom
            TextField::new('name'),
            TextareaField::new('composants'),
            //définir la money dans laquelle on travail
            MoneyField::new('price')->setCurrency('EUR'),
            //permet d'avoir des URL plus jolie
            SlugField::new('slug')->setTargetFieldName('name'),//se baser sur le nom de mon produit pour générer un slug
            AssociationField::new('category'),
            AssociationField::new('ingredients'),
            
            //Je veux afficher un upload qui permet de charger une image 
            ImageField::new('image')
            //une methode qui permet d'indiquer le dossier ou mettre nos images
            ->setBasePath('uploads/images/product')
            //mettre le chemin complet afin de  définir le répertoire où les images sont téléchargées.
            ->setUploadDir('public/uploads/images/product')
            //la maniere d'on veut encoder nos fichiers images[randomhash-chaine de carractère]
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            
            ->setRequired(false),
        ];
    }
    
}