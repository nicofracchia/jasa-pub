<?php

namespace App\Repository;

use App\Entity\VentasProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VentasProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method VentasProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method VentasProductos[]    findAll()
 * @method VentasProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentasProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VentasProductos::class);
    }

    // /**
    //  * @return VentasProductos[] Returns an array of VentasProductos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VentasProductos
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
