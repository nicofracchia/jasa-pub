<?php

namespace App\Repository;

use App\Entity\ComprasProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComprasProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComprasProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComprasProductos[]    findAll()
 * @method ComprasProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComprasProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComprasProductos::class);
    }

    // /**
    //  * @return ComprasProductos[] Returns an array of ComprasProductos objects
    //  */
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
    }
    */

    /*
    public function findOneBySomeField($value): ?ComprasProductos
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
