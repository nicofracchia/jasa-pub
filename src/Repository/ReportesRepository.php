<?php

namespace App\Repository;

use App\Entity\Reportes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reportes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reportes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reportes[]    findAll()
 * @method Reportes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reportes::class);
    }

    public function getMediosPago($desde, $hasta)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT SUM(vp.monto) AS total, mp.nombre ";
        $SQL .= "FROM ventas_pagos AS vp ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON mp.id = vp.medio_pago_id ";
        $SQL .= "WHERE vp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "GROUP BY mp.nombre ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getUsuarios($desde, $hasta)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT SUM(vp.monto) AS total, CONCAT_WS(' ',u.nombre,u.apellido) AS usuario ";
        $SQL .= "FROM ventas AS v ";
        $SQL .= "INNER JOIN usuarios AS u ";
        $SQL .= "    ON v.creador_id = u.id ";
        $SQL .= "INNER JOIN ventas_pagos AS vp ";
        $SQL .= "    ON v.id = vp.venta_id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON mp.id = vp.medio_pago_id ";
        $SQL .= "WHERE v.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "GROUP BY u.id ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getClientes($desde, $hasta, $cliente)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT SUM(vp.monto) AS total, CONCAT_WS(' ',c.nombre,c.apellido) AS cliente ";
        $SQL .= "FROM ventas AS v ";
        $SQL .= "INNER JOIN clientes AS c ";
        $SQL .= "    ON v.cliente_id = c.id ";
        $SQL .= "INNER JOIN ventas_pagos AS vp ";
        $SQL .= "    ON v.id = vp.venta_id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON mp.id = vp.medio_pago_id ";
        $SQL .= "WHERE v.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= ($cliente != '') ? "    AND v.cliente_id = ".$cliente." " : "";
        $SQL .= "GROUP BY c.id ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getProductos($desde, $hasta)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT p.titulo, SUM(vp.cantidad) AS cantidad, SUM(vp.costo) AS costo, SUM(vp.precio) AS precio, um.nombre AS unidad_medida ";
        $SQL .= "FROM ventas_productos AS vp ";
        $SQL .= "INNER JOIN ventas AS v ";
        $SQL .= "    ON vp.venta_id = v.id ";
        $SQL .= "INNER JOIN productos AS p ";
        $SQL .= "    ON vp.producto_id = p.id ";
        $SQL .= "INNER JOIN unidades_medida AS um ";
        $SQL .= "   ON p.id_unidad_medida_id = um.id ";
        $SQL .= "WHERE v.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "GROUP BY vp.producto_id ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getMovimientos($desde, $hasta)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT v.id AS id_venta, a.nombre AS almacen, vp.fecha, 'Venta' AS tipo, vp.monto, mp.nombre AS medio_pago, GROUP_CONCAT(CONCAT_WS(': ',vpd.nombre,DATE_FORMAT(vpd.valor,'%d/%m/%Y'))) AS detalle_medio, vp.observaciones ";
        $SQL .= "FROM ventas_pagos AS vp ";
        $SQL .= "INNER JOIN ventas AS v ";
        $SQL .= "    ON vp.venta_id = v.id ";
        $SQL .= "INNER JOIN almacenes AS a ";
        $SQL .= "    ON v.almacen_id = a.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON vp.medio_pago_id = mp.id ";
        $SQL .= "LEFT JOIN ventas_pagos_detalle AS vpd ";
        $SQL .= "    ON vp.id = vpd.pago_id ";
        $SQL .= "WHERE vp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "GROUP BY vp.id ";
        $SQL .= "UNION ";
        $SQL .= "SELECT 0 AS id_venta, a.nombre AS almacen, rp.fecha, 'Reparación' AS tipo, rp.monto, mp.nombre AS medio_pago, GROUP_CONCAT(CONCAT_WS(': ',rpd.nombre,DATE_FORMAT(rpd.valor,'%d/%m/%Y'))) AS detalle_medio, rp.observaciones ";
        $SQL .= "FROM reparaciones_pagos AS rp ";
        $SQL .= "INNER JOIN reparaciones AS r ";
        $SQL .= "    ON rp.reparacion_id = r.id ";
        $SQL .= "INNER JOIN almacenes AS a ";
        $SQL .= "    ON r.almacen_id = a.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON rp.medio_pago_id = mp.id ";
        $SQL .= "LEFT JOIN reparaciones_pagos_detalle AS rpd ";
        $SQL .= "    ON rp.id = rpd.pago_id ";
        $SQL .= "WHERE rp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "GROUP BY rp.id ";
        $SQL .= "ORDER BY  ";
        $SQL .= "    almacen ASC, ";
        $SQL .= "    fecha ASC ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getCajaDiaria($desde, $hasta)
    {
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT DATE_FORMAT(c.inicio,'%d/%m/%Y %H:%i:%s') AS fecha, 'Apertura de caja' AS movimiento, c.saldo_inicial AS monto, ' ' AS observaciones ";
        $SQL .= "FROM caja AS c ";
        $SQL .= "WHERE c.inicio BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT DATE_FORMAT(c.cierre,'%d/%m/%Y %H:%i:%s') AS fecha, 'Cierre de caja' AS movimiento, c.saldo_final AS monto, c.saldo_estimado AS observaciones ";
        $SQL .= "FROM caja AS c ";
        $SQL .= "WHERE c.inicio BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT DATE_FORMAT(mc.fecha,'%d/%m/%Y %H:%i:%s') AS fecha, mc.movimiento, mc.monto, mc.observaciones ";
        $SQL .= "FROM movimientos_caja AS mc ";
        $SQL .= "WHERE mc.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT DATE_FORMAT(vp.fecha,'%d/%m/%Y %H:%i:%s') AS fecha, 'Pago por venta' AS movimiento, vp.monto, vp.observaciones ";
        $SQL .= "FROM ventas_pagos AS vp ";
        $SQL .= "WHERE  ";
        $SQL .= "    vp.medio_pago_id = 1 AND ";
        $SQL .= "    vp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "UNION ";
        $SQL .= "SELECT DATE_FORMAT(rp.fecha,'%d/%m/%Y %H:%i:%s') AS fecha, 'Pago por reparación' AS movimiento, rp.monto, rp.observaciones ";
        $SQL .= "FROM reparaciones_pagos AS rp ";
        $SQL .= "WHERE  ";
        $SQL .= "    rp.medio_pago_id = 1 AND ";
        $SQL .= "    rp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "ORDER BY fecha ASC ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function getClientesDetalle($desde, $hasta, $cliente){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT ";
        $SQL .= "    c.razon_social AS cliente_rs, ";
        $SQL .= "    c.nombre AS cliente_nombre, ";
        $SQL .= "    c.apellido AS cliente_apellido, ";
        $SQL .= "    u.nombre AS usuario_nombre, ";
        $SQL .= "    u.apellido AS usuario_apellido, ";
        $SQL .= "    vp.fecha AS fecha, ";
        $SQL .= "    'VENTA' AS motivo, ";
        $SQL .= "    mp.nombre AS medio_pago, ";
        $SQL .= "    vp.monto AS monto, ";
        $SQL .= "    vp.observaciones AS observaciones ";
        $SQL .= "FROM ventas_pagos AS vp ";
        $SQL .= "INNER JOIN ventas AS v ";
        $SQL .= "    ON vp.venta_id = v.id ";
        $SQL .= "INNER JOIN clientes AS c ";
        $SQL .= "    ON v.cliente_id = c.id ";
        $SQL .= "INNER JOIN usuarios AS u ";
        $SQL .= "    ON v.creador_id = u.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON vp.medio_pago_id = mp.id ";
        $SQL .= "WHERE ";
        $SQL .= "    vp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= ($cliente != '') ? "    AND v.cliente_id = ".$cliente." " : "";
        $SQL .= "UNION ";
        $SQL .= "SELECT ";
        $SQL .= "    c.razon_social AS cliente_rs, ";
        $SQL .= "    c.nombre AS cliente_nombre, ";
        $SQL .= "    c.apellido AS cliente_apellido, ";
        $SQL .= "    u.nombre AS usuario_nombre, ";
        $SQL .= "    u.apellido AS usuario_apellido, ";
        $SQL .= "    rp.fecha AS fecha, ";
        $SQL .= "    'REPARACION' AS motivo, ";
        $SQL .= "    mp.nombre AS medio_pago, ";
        $SQL .= "    rp.monto AS monto, ";
        $SQL .= "    rp.observaciones AS observaciones ";
        $SQL .= "FROM reparaciones_pagos AS rp ";
        $SQL .= "INNER JOIN reparaciones AS r ";
        $SQL .= "    ON rp.reparacion_id = r.id ";
        $SQL .= "INNER JOIN clientes AS c ";
        $SQL .= "    ON r.cliente_id = c.id ";
        $SQL .= "INNER JOIN usuarios AS u ";
        $SQL .= "    ON r.receptor_id = u.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON rp.medio_pago_id = mp.id ";
        $SQL .= "WHERE ";
        $SQL .= "    rp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= ($cliente != '') ? "    AND r.cliente_id = ".$cliente." " : "";
        $SQL .= "ORDER BY fecha DESC ";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getMediosPagoDetalle($desde, $hasta, $medio){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT ";
        $SQL .= "    vp.fecha AS fecha, ";
        $SQL .= "    mp.nombre AS medios_pago, ";
        $SQL .= "    vp.monto AS monto, ";
        $SQL .= "    'VENTA' AS motivo, ";
        $SQL .= "    vp.observaciones AS observaciones ";
        $SQL .= "FROM ventas_pagos AS vp ";
        $SQL .= "INNER JOIN ventas AS v ";
        $SQL .= "    ON vp.venta_id = v.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON vp.medio_pago_id = mp.id ";
        $SQL .= "WHERE  ";
        $SQL .= "    vp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= "    AND medio_pago_id = 3 ";
        $SQL .= ($medio != '') ? "    AND vp.medio_pago_id = ".$medio." " : "";
        $SQL .= "UNION ";
        $SQL .= "SELECT ";
        $SQL .= "    rp.fecha AS fecha, ";
        $SQL .= "    mp.nombre AS medios_pago, ";
        $SQL .= "    rp.monto AS monto, ";
        $SQL .= "    'REPARACION' AS motivo, ";
        $SQL .= "    rp.observaciones AS observaciones ";
        $SQL .= "FROM reparaciones_pagos AS rp ";
        $SQL .= "INNER JOIN reparaciones AS r ";
        $SQL .= "    ON rp.reparacion_id = r.id ";
        $SQL .= "INNER JOIN medios_pago AS mp ";
        $SQL .= "    ON rp.medio_pago_id = mp.id ";
        $SQL .= "WHERE  ";
        $SQL .= "    rp.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= ($medio != '') ? "    AND rp.medio_pago_id = ".$medio." " : "";
        $SQL .= "ORDER BY fecha DESC ";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getVentasProductosUsuarios($desde, $hasta, $producto, $usuario){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT ";
        $SQL .= "    v.fecha AS fecha, ";
        $SQL .= "    a.nombre AS almacen, ";
        $SQL .= "    prod.titulo AS producto, ";
        $SQL .= "    vpr.cantidad AS cantidad, ";
        $SQL .= "    um.corto AS unidad_medida, ";
        $SQL .= "    vpr.precio AS precio_unitario, ";
        $SQL .= "    (vpr.precio * vpr.cantidad) AS precio_total, ";
        $SQL .= "    CONCAT(IF(c.razon_social IS NOT NULL AND c.razon_social <> '', CONCAT(c.razon_social,' - '), ''),  c.nombre,' ',c.apellido) AS cliente, ";
        $SQL .= "    CONCAT(u.nombre,' ',u.apellido) AS usuario ";
        $SQL .= "FROM ventas_productos AS vpr ";
        $SQL .= "INNER JOIN productos AS prod ON vpr.producto_id = prod.id ";
        $SQL .= "INNER JOIN unidades_medida AS um ON prod.id_unidad_medida_id = um.id ";
        $SQL .= "INNER JOIN ventas AS v ON vpr.venta_id = v.id ";
        $SQL .= "INNER JOIN almacenes AS a ON v.almacen_id = a.id ";
        $SQL .= "INNER JOIN usuarios AS u ON v.creador_id = u.id ";
        $SQL .= "LEFT JOIN clientes AS c ON v.cliente_id = c.id ";
        $SQL .= "WHERE  ";
        $SQL .= "    v.fecha BETWEEN '".$desde."' AND '".$hasta."' ";
        $SQL .= ($usuario != '') ? "    AND u.id = ".$usuario." " : "";
        $SQL .= ($producto != '') ? "    AND prod.id = ".$producto." " : "";
        $SQL .= "GROUP BY vpr.id ";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
