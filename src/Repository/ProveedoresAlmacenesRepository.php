<?php

namespace App\Repository;

use App\Entity\ProveedoresAlmacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProveedoresAlmacenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProveedoresAlmacenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProveedoresAlmacenes[]    findAll()
 * @method ProveedoresAlmacenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProveedoresAlmacenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProveedoresAlmacenes::class);
    }

    // /**
    //  * @return ProveedoresAlmacenes[] Returns an array of ProveedoresAlmacenes objects
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
    public function findOneBySomeField($value): ?ProveedoresAlmacenes
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
