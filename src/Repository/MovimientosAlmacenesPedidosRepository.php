<?php

namespace App\Repository;

use App\Entity\MovimientosAlmacenesPedidos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MovimientosAlmacenesPedidos|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovimientosAlmacenesPedidos|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovimientosAlmacenesPedidos[]    findAll()
 * @method MovimientosAlmacenesPedidos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovimientosAlmacenesPedidosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MovimientosAlmacenesPedidos::class);
    }

    public function eliminarProductosPedido($pedido){
        $conn = $this->getEntityManager()->getConnection();
        
        $SQL = "DELETE FROM movimientos_almacenes_pedidos WHERE movimiento_id = ".$pedido;
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
    }
}
