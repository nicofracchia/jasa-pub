<?php

namespace App\Repository;

use App\Entity\ProveedoresProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProveedoresProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProveedoresProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProveedoresProductos[]    findAll()
 * @method ProveedoresProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProveedoresProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProveedoresProductos::class);
    }

    // /**
    //  * @return ProveedoresProductos[] Returns an array of ProveedoresProductos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProveedoresProductos
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
