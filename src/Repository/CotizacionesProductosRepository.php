<?php

namespace App\Repository;

use App\Entity\CotizacionesProductos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CotizacionesProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CotizacionesProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CotizacionesProductos[]    findAll()
 * @method CotizacionesProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotizacionesProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CotizacionesProductos::class);
    }

    // /**
    //  * @return CotizacionesProductos[] Returns an array of CotizacionesProductos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CotizacionesProductos
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
