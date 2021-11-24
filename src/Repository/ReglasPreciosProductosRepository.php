<?php

namespace App\Repository;

use App\Entity\ReglasPreciosProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReglasPreciosProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReglasPreciosProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReglasPreciosProductos[]    findAll()
 * @method ReglasPreciosProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReglasPreciosProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReglasPreciosProductos::class);
    }

    // /**
    //  * @return ReglasPreciosProductos[] Returns an array of ReglasPreciosProductos objects
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
    public function findOneBySomeField($value): ?ReglasPreciosProductos
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
