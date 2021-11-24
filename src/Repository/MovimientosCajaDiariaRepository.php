<?php

namespace App\Repository;

use App\Entity\MovimientosCajaDiaria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosCajaDiaria|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosCajaDiaria|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosCajaDiaria[]    findAll()
 * @method MovimientosCajaDiaria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosCajaDiariaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosCajaDiaria::class);
    }

    // /**
    //  * @return MovimientosCajaDiaria[] Returns an array of MovimientosCajaDiaria objects
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
    public function findOneBySomeField($value): ?MovimientosCajaDiaria
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
