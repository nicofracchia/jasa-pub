<?php

namespace App\Repository;

use App\Entity\Almacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Almacenes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Almacenes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Almacenes[]    findAll()
 * @method Almacenes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlmacenesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Almacenes::class);
    }

    /**
     * @return Almacenes[] Returns an array of Almacenes objects
     */
    public function findAllVigentes()
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.eliminado = :val')
            ->andWhere('a.id != 1')
            ->setParameter('val', 0)
            ->orderBy('a.nombre', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Almacenes
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
