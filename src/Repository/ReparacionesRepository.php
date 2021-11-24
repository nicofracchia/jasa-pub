<?php

namespace App\Repository;

use App\Entity\Reparaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reparaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reparaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reparaciones[]    findAll()
 * @method Reparaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reparaciones::class);
    }

    // /**
    //  * @return Reparaciones[] Returns an array of Reparaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reparaciones
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
