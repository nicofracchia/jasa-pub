<?php

namespace App\Repository;

use App\Entity\CategoriasProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoriasProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoriasProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoriasProductos[]    findAll()
 * @method CategoriasProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriasProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoriasProductos::class);
    }

    // /**
    //  * @return CategoriasProductos[] Returns an array of CategoriasProductos objects
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
    public function findOneBySomeField($value): ?CategoriasProductos
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
