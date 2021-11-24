<?php

namespace App\Repository;

use App\Entity\Caja;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Caja|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caja|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caja[]    findAll()
 * @method Caja[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CajaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caja::class);
    }

    public function findCajaAbiertaPendiente(){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT * FROM caja WHERE inicio < DATE(NOW()) AND estado = 0";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findCajaAbiertaHoy(){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT * FROM caja WHERE DATE(inicio) = DATE(NOW()) AND estado = 0";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findMovimientosHoy($fechaInicio){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT  ";
        $SQL .= "    DATE(fecha) AS fecha, ";
        $SQL .= "    TIME(fecha) AS hora, ";
        $SQL .= "    movimiento, ";
        $SQL .= "    monto, ";
        $SQL .= "    tipo_movimiento AS tipo, ";
        $SQL .= "    observaciones ";
        $SQL .= "FROM movimientos_caja ";
        $SQL .= "WHERE fecha > '".$fechaInicio."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT  ";
        $SQL .= "    DATE(fecha) AS fecha, ";
        $SQL .= "    TIME(fecha) AS hora, ";
        $SQL .= "    \"Pago por venta\" AS movimiento, ";
        $SQL .= "    monto, ";
        $SQL .= "    1 AS tipo, ";
        $SQL .= "    observaciones ";
        $SQL .= "FROM ventas_pagos ";
        $SQL .= "WHERE fecha > '".$fechaInicio."' AND medio_pago_id = 1 ";
        $SQL .= "UNION ";
        $SQL .= "SELECT  ";
        $SQL .= "    DATE(fecha) AS fecha, ";
        $SQL .= "    TIME(fecha) AS hora, ";
        $SQL .= "    \"Pago por reparación\" AS movimiento, ";
        $SQL .= "    monto, ";
        $SQL .= "    1 AS tipo, ";
        $SQL .= "    observaciones ";
        $SQL .= "FROM reparaciones_pagos ";
        $SQL .= "WHERE fecha > '".$fechaInicio."' AND medio_pago_id = 1 ";
        $SQL .= "ORDER BY hora DESC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findMontoEstimado($fecha){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT ";
        $SQL .= "    SUM(IF(tipo_movimiento = 1, monto, monto * -1)) AS m ";
        $SQL .= "FROM movimientos_caja ";
        $SQL .= "WHERE fecha > '".$fecha."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT ";
        $SQL .= "    SUM(monto) AS m ";
        $SQL .= "FROM ventas_pagos ";
        $SQL .= "WHERE fecha > '".$fecha."' AND medio_pago_id = 1 ";
        $SQL .= "UNION ";
        $SQL .= "SELECT ";
        $SQL .= "    SUM(monto) AS m ";
        $SQL .= "FROM reparaciones_pagos ";
        $SQL .= "WHERE fecha > '".$fecha."' AND medio_pago_id = 1";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findUltimoCierre(){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT  ";
        $SQL .= "   c.inicio,  ";
        $SQL .= "   c.cierre,  ";
        $SQL .= "   c.saldo_final,  ";
        $SQL .= "   c.saldo_estimado,  ";
        $SQL .= "   c.saldo_final - c.saldo_estimado AS diferencia,  ";
        $SQL .= "   CONCAT_WS(' ', u.nombre, u.apellido) AS usuario_cierre ";
        $SQL .= "FROM caja AS c ";
        $SQL .= "INNER JOIN usuarios AS u ";
        $SQL .= "   ON c.usuario_cierre_id = u.id ";
        $SQL .= "WHERE  c.estado = 1 ";
        $SQL .= "ORDER BY inicio DESC ";
        $SQL .= "LIMIT 0, 1 ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
