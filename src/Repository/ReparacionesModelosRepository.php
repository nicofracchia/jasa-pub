<?php

namespace App\Repository;

use App\Entity\ReparacionesModelos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesModelos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesModelos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesModelos[]    findAll()
 * @method ReparacionesModelos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesModelosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesModelos::class);
    }

    // /**
    //  * @return ReparacionesModelos[] Returns an array of ReparacionesModelos objects
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
    public function findOneBySomeField($value): ?ReparacionesModelos
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
