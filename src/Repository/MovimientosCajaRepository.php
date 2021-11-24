<?php

namespace App\Repository;

use App\Entity\MovimientosCaja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosCaja|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosCaja|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosCaja[]    findAll()
 * @method MovimientosCaja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosCajaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosCaja::class);
    }

    // /**
    //  * @return MovimientosCaja[] Returns an array of MovimientosCaja objects
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
    public function findOneBySomeField($value): ?MovimientosCaja
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
