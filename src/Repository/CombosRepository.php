<?php

namespace App\Repository;

use App\Entity\Combos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Combos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Combos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Combos[]    findAll()
 * @method Combos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Combos::class);
    }

    /**
     * @return Combos[] Returns an array of Combos objects
     */
    public function findAllVigentes()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.eliminado = :val')
            ->setParameter('val', 0)
            ->orderBy('c.nombre', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function eliminarProductosCombo($combo){
        $conn = $this->getEntityManager()->getConnection();
        
        $SQL = "DELETE FROM combos_productos WHERE id_combo_id = ".$combo;
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
    }
    
    public function getStockProductosCombo($idCombo, $idAlmacen){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT p.id, p.titulo, pa.stock ";
        $SQL .= "FROM combos_productos AS cp ";
        $SQL .= "INNER JOIN productos_almacenes AS pa ";
        $SQL .= "    ON cp.id_producto_id = pa.id_producto_id ";
        $SQL .= "INNER JOIN productos AS p ";
        $SQL .= "    ON p.id = pa.id_producto_id ";
        $SQL .= "WHERE ";
        $SQL .= "    cp.id_combo_id = ".$idCombo."  AND ";
        $SQL .= "    pa.id_almacen_id = ".$idAlmacen." ";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
