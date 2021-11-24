<?php

namespace App\Repository;

use App\Entity\Proveedores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Proveedores|null find($id, $lockMode = null, $lockVersion = null)
 * @method Proveedores|null findOneBy(array $criteria, array $orderBy = null)
 * @method Proveedores[]    findAll()
 * @method Proveedores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProveedoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proveedores::class);
    }

    /**
     * @return Proveedores[] Returns an array of Proveedores objects
     */
    public function findAllVigentes()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.eliminado = :val')
            ->setParameter('val', 0)
            ->orderBy('p.nombre', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Proveedores
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
