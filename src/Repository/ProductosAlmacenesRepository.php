<?php

namespace App\Repository;

use App\Entity\ProductosAlmacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductosAlmacenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductosAlmacenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductosAlmacenes[]    findAll()
 * @method ProductosAlmacenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductosAlmacenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductosAlmacenes::class);
    }

    // /**
    //  * @return ProductosAlmacenes[] Returns an array of ProductosAlmacenes objects
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
    public function findOneBySomeField($value): ?ProductosAlmacenes
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
