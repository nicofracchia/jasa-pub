<?php

namespace App\Repository;

use App\Entity\VentasEnvios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VentasEnvios|null find($id, $lockMode = null, $lockVersion = null)
 * @method VentasEnvios|null findOneBy(array $criteria, array $orderBy = null)
 * @method VentasEnvios[]    findAll()
 * @method VentasEnvios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentasEnviosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VentasEnvios::class);
    }

    // /**
    //  * @return VentasEnvios[] Returns an array of VentasEnvios objects
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
    public function findOneBySomeField($value): ?VentasEnvios
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
