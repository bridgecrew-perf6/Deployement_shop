<?php

namespace App\Repository;
use App\Entity\Commande;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $commandeBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $commandeBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /*
    * findSuccessCommandes()
    * Permet d'afficher les commandes dans l'espace membre de l'utilisateur
    */
   public function findSuccessCommandes($user)
{
    return $this->createQueryBuilder('c')//alias de Commande
           ->andWhere('c.state > 0')//condition aller chercher dans Commande state quand il est > 0
           ->andWhere('c.user = :user')
           ->setParameter('user', $user)
           ->orderBy('c.id', 'DESC')
           ->getQuery()
           ->getResult();
    }

     ///*
     // * @return Commande[] Returns an array of Commande objects
     // */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }*/
    

    /*
    public function findOneBySomeField($value): ?Commande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}