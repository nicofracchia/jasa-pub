<?php

namespace App\Repository;

use App\Entity\MotivosAjustesStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MotivosAjustesStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotivosAjustesStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotivosAjustesStock[]    findAll()
 * @method MotivosAjustesStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotivosAjustesStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotivosAjustesStock::class);
    }

    // /**
    //  * @return MotivosAjustesStock[] Returns an array of MotivosAjustesStock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MotivosAjustesStock
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
