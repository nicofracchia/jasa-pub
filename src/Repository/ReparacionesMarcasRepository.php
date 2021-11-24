<?php

namespace App\Repository;

use App\Entity\ReparacionesMarcas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReparacionesMarcas|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReparacionesMarcas|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReparacionesMarcas[]    findAll()
 * @method ReparacionesMarcas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReparacionesMarcasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReparacionesMarcas::class);
    }

    // /**
    //  * @return ReparacionesMarcas[] Returns an array of ReparacionesMarcas objects
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
    public function findOneBySomeField($value): ?ReparacionesMarcas
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
