<?php

namespace App\Repository;

use App\Entity\MovimientosAlmacenesRecepciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosAlmacenesRecepciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosAlmacenesRecepciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosAlmacenesRecepciones[]    findAll()
 * @method MovimientosAlmacenesRecepciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosAlmacenesRecepcionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosAlmacenesRecepciones::class);
    }

    // /**
    //  * @return MovimientosAlmacenesRecepciones[] Returns an array of MovimientosAlmacenesRecepciones objects
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
    public function findOneBySomeField($value): ?MovimientosAlmacenesRecepciones
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
