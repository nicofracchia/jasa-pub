<?php

namespace App\Repository;

use App\Entity\MovimientosAlmacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosAlmacenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosAlmacenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosAlmacenes[]    findAll()
 * @method MovimientosAlmacenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosAlmacenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosAlmacenes::class);
    }

    // /**
    //  * @return MovimientosAlmacenes[] Returns an array of MovimientosAlmacenes objects
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
    public function findOneBySomeField($value): ?MovimientosAlmacenes
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
