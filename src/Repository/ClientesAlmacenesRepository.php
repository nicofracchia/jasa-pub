<?php

namespace App\Repository;

use App\Entity\ClientesAlmacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientesAlmacenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientesAlmacenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientesAlmacenes[]    findAll()
 * @method ClientesAlmacenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientesAlmacenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientesAlmacenes::class);
    }

    // /**
    //  * @return ClientesAlmacenes[] Returns an array of ClientesAlmacenes objects
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
    public function findOneBySomeField($value): ?ClientesAlmacenes
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
