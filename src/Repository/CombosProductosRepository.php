<?php

namespace App\Repository;

use App\Entity\CombosProductos;
use App\Entity\Productos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CombosProductos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CombosProductos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CombosProductos[]    findAll()
 * @method CombosProductos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombosProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CombosProductos::class);
    }

    /**
     * @return CombosProductos[] Returns an array of CombosProductos objects
     */
    public function findProductosByCombo($idCombo)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('App\Entity\Productos', 'p', 'WITH', 'p.id = c.id_producto')
            ->andWhere('c.id_combo = :idCombo')
            ->setParameter('idCombo', $idCombo)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?CombosProductos
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
