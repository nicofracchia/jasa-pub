<?php

namespace App\Repository;

use App\Entity\MovimientosAlmacenesEnvios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosAlmacenesEnvios|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosAlmacenesEnvios|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosAlmacenesEnvios[]    findAll()
 * @method MovimientosAlmacenesEnvios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosAlmacenesEnviosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosAlmacenesEnvios::class);
    }

    public function eliminarProductosEnvio($pedido){
        $conn = $this->getEntityManager()->getConnection();
        
        $SQL = "DELETE FROM movimientos_almacenes_envios WHERE movimiento_id = ".$pedido;
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
    }
}
