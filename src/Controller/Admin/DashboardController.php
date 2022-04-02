<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Commande;
use App\Entity\Ingredient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    //une route qui est affecté a admin
    /**
     * @Route("/admin", name="admin")
     */

    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(CommandeCrudController::class)->generateUrl());

    }
   
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HairCare');
    }

    public function configureMenuItems(): iterable// pour configurer le menu avec la fonction confiureMenuItems
    {
        //on créant le crud controller on permet a easy admin de se brancher avec les differntes routes
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Ingredients', 'fa fa-list', Ingredient::class);
        yield MenuItem::linkToCrud('Products', 'fa fa-tag', Product::class);
        yield MenuItem::linkToCrud('Carriers', 'fa fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('Commandes', 'fa fa-shopping-cart', Commande::class);
        
    }
}