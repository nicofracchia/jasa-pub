<?php

namespace App\Repository;

use App\Entity\ReparacionesPagosDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesPagosDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesPagosDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesPagosDetalle[]    findAll()
 * @method ReparacionesPagosDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesPagosDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesPagosDetalle::class);
    }

    // /**
    //  * @return ReparacionesPagosDetalle[] Returns an array of ReparacionesPagosDetalle objects
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
    public function findOneBySomeField($value): ?ReparacionesPagosDetalle
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
