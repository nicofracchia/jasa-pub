<?php

namespace App\Repository;

use App\Entity\AjustesStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AjustesStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method AjustesStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method AjustesStock[]    findAll()
 * @method AjustesStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AjustesStockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AjustesStock::class);
    }

    /**
     * @return AjustesStock[] Returns an array of AjustesStock objects
     */
    public function listadoAjustesPorProducto($idProducto)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.producto = :val')
            ->setParameter('val', $idProducto)
            ->orderBy('a.fecha', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?AjustesStock
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
