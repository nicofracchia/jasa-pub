<?php

namespace App\Repository;

use App\Entity\VentasPagosDetalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VentasPagosDetalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method VentasPagosDetalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method VentasPagosDetalle[]    findAll()
 * @method VentasPagosDetalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentasPagosDetalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VentasPagosDetalle::class);
    }

    // /**
    //  * @return VentasPagosDetalle[] Returns an array of VentasPagosDetalle objects
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
    public function findOneBySomeField($value): ?VentasPagosDetalle
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
