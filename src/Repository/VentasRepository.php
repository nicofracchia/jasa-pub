<?php

namespace App\Repository;

use App\Entity\Ventas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ventas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ventas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ventas[]    findAll()
 * @method Ventas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VentasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ventas::class);
    }

    public function sumaPagos($ventaId){
        $conn = $this->getEntityManager()->getConnection();
        $SQL = "SELECT SUM(monto) AS total FROM ventas_pagos WHERE venta_id = ".$ventaId." LIMIT 1";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetch();
    }

    public function sumaPrecioProductos($ventaId){
        $conn = $this->getEntityManager()->getConnection();
        $SQL = "SELECT sum(cantidad * precio) AS total FROM ventas_productos WHERE venta_id = ".$ventaId." LIMIT 1";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetch();
    }

    public function findAllHoy(){
        $conn = $this->getEntityManager()->getConnection();
        $SQL = "SELECT * FROM ventas WHERE fecha >= CURDATE() ORDER BY id DESC";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetchAll();
    }

    public function findAllFiltrado($filtros){
        $conn = $this->getEntityManager()->getConnection();
        
        $whereCliente = $filtros['cliente'];
        $whereVendedor = $filtros['vendedor'];

        $SQL  = "SELECT ventas.* FROM ventas ";

        if($filtros['cliente'] != ''){
            $SQL .= " INNER JOIN clientes ON ventas.cliente_id = clientes.id ";
            $whereCliente  = " AND (";
            $whereCliente .= " clientes.nombre LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.apellido LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.mail LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.dni LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.telefono LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.direccion LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.cuit LIKE '%".$filtros['cliente']."%' OR ";
            $whereCliente .= " clientes.razon_social LIKE '%".$filtros['cliente']."%'  ";
            $whereCliente .= " ) ";
        }

        if($filtros['vendedor'] != ''){
            $SQL .= " INNER JOIN usuarios ON ventas.creador_id = usuarios.id ";
            $whereVendedor  = " AND (";
            $whereVendedor .= " usuarios.email LIKE '%".$filtros['vendedor']."%' OR ";
            $whereVendedor .= " usuarios.nombre LIKE '%".$filtros['vendedor']."%' OR ";
            $whereVendedor .= " usuarios.apellido LIKE '%".$filtros['vendedor']."%' OR ";
            $whereVendedor .= " usuarios.direccion LIKE '%".$filtros['vendedor']."%' ";
            $whereVendedor .= " ) ";
        }

        $SQL .= "WHERE ";
        $SQL .= ($filtros['desde'] != '') ? "ventas.fecha >= '".$filtros['desde']." 00:00:00' AND " : "fecha >= CURDATE() AND ";
        $SQL .= ($filtros['hasta'] != '') ? "ventas.fecha <= '".$filtros['hasta']." 23:59:59' " : "fecha <= CURDATE() ";
        $SQL .= ($filtros['estado'] != '') ? " AND ventas.estado = '".$filtros['estado']."' " : " ";
        $SQL .= $whereCliente;
        $SQL .= $whereVendedor;
        $SQL .= "ORDER BY ventas.id DESC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetchAll();
    }
}
