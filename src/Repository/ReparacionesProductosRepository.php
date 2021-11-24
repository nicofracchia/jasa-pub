<?php

namespace App\Repository;

use App\Entity\ReparacionesProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesProductos[]    findAll()
 * @method ReparacionesProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesProductos::class);
    }

    // /**
    //  * @return ReparacionesProductos[] Returns an array of ReparacionesProductos objects
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
    public function findOneBySomeField($value): ?ReparacionesProductos
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
