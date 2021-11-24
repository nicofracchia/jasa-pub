<?php

namespace App\Repository;

use App\Entity\ReparacionesEstados;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesEstados|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesEstados|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesEstados[]    findAll()
 * @method ReparacionesEstados[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesEstadosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesEstados::class);
    }

    // /**
    //  * @return ReparacionesEstados[] Returns an array of ReparacionesEstados objects
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
    public function findOneBySomeField($value): ?ReparacionesEstados
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
