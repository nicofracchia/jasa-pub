<?php

namespace App\Repository;

use App\Entity\ReparacionesPagos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesPagos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesPagos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesPagos[]    findAll()
 * @method ReparacionesPagos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesPagosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesPagos::class);
    }

    // /**
    //  * @return ReparacionesPagos[] Returns an array of ReparacionesPagos objects
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
    public function findOneBySomeField($value): ?ReparacionesPagos
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
