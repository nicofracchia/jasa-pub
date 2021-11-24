<?php

namespace App\Repository;

use App\Entity\Productos;
use App\Entity\ProductosAlmacenes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Productos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Productos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Productos[]    findAll()
 * @method Productos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Productos::class);
    }

    /**
     * @return Productos[] Returns an array of Productos objects
     */
    public function findAllVigentes($filtros)
    {

        $SQL  = "SELECT p, SUM(pa.stock) as stock FROM App\Entity\Productos p ";
        $SQL .= "INNER JOIN App\Entity\ProductosAlmacenes pa ";
	    $SQL .= " WITH p.id = pa.id_producto ";
        $SQL .= "WHERE p.eliminado = :eliminado ";
        isset($filtros['titulo']) ? $SQL .= " AND p.titulo LIKE :titulo ":"";
        isset($filtros['id']) ? $SQL .= " AND p.id LIKE :id ":"";
        isset($filtros['codigo_barras']) ? $SQL .= "AND p.codigo_barras LIKE :codigo_barras ":"";
        $SQL .= "GROUP BY p.id ";
        $SQL .= "ORDER BY p.titulo ASC";
        $SQL = $this->getEntityManager()->createQuery($SQL);
        isset($filtros['titulo']) ? $SQL->setParameter('titulo', '%'.$filtros['titulo'].'%'):"";
        isset($filtros['id']) ? $SQL->setParameter('id', '%'.$filtros['id'].'%'):"";
        isset($filtros['codigo_barras']) ? $SQL->setParameter('codigo_barras', '%'.$filtros['codigo_barras'].'%'):"";
        $SQL = $SQL->setParameter('eliminado', 0);
        $SQL = $SQL->getResult();

        return $SQL;
    }

    public function alertas(){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT p.id, p.titulo, a.nombre AS almacen, p.stock_minimo, pa.stock, p.costo, p.precio_final AS precio ";
        $SQL .= "FROM productos AS p ";
        $SQL .= "INNER JOIN productos_almacenes AS pa ";
        $SQL .= "    ON pa.id_producto_id = p.id ";
        $SQL .= "INNER JOIN almacenes AS a ";
        $SQL .= "    ON pa.id_almacen_id = a.id ";
        $SQL .= "WHERE ";
        $SQL .= "    p.habilitado = 1 AND ";
        $SQL .= "    p.eliminado = 0 ";
        $SQL .= "ORDER BY pa.stock ASC, p.titulo ASC, a.nombre ASC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    public function findAllBusqueda($idAlmacen, $busqueda){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT p.id, p.precio_final, p.titulo, pa.stock, um.corto, p.iva, p.precio_final AS precio_venta ";
        $SQL .= "FROM productos_almacenes AS pa ";
        $SQL .= "INNER JOIN productos AS p ";
        $SQL .= "   ON pa.id_producto_id = p.id ";
        $SQL .= "INNER JOIN unidades_medida AS um ";
        $SQL .= "   ON um.id = p.id_unidad_medida_id ";
        $SQL .= "WHERE ";
        foreach($busqueda as $b){
            $SQL .= "    titulo LIKE '%".$b."%' AND ";
        }
        $SQL .= ($idAlmacen != 0) ? "    pa.id_almacen_id = ".$idAlmacen." AND " : '';
        $SQL .= "    p.eliminado = 0 AND ";
        $SQL .= "    (p.material_reparacion = 0 OR ISNULL(p.material_reparacion)) AND ";
        $SQL .= "    p.habilitado = 1 ";
        $SQL .= "GROUP BY p.id ";
        $SQL .= "ORDER BY ";
        $SQL .= "    titulo ASC, ";
        $SQL .= "    precio_final ASC ";
        
        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetchAll();
    }

    public function findAllBusquedaMateriales($idAlmacen, $busqueda){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT p.id, p.precio_final, p.titulo, pa.stock, um.corto, p.iva, p.precio_final AS precio_venta ";
        $SQL .= "FROM productos_almacenes AS pa ";
        $SQL .= "INNER JOIN productos AS p ";
        $SQL .= "   ON pa.id_producto_id = p.id ";
        $SQL .= "INNER JOIN unidades_medida AS um ";
        $SQL .= "   ON um.id = p.id_unidad_medida_id ";
        $SQL .= "WHERE ";
        foreach($busqueda as $b){
            $SQL .= "    titulo LIKE '%".$b."%' AND ";
        }
        $SQL .= "    pa.id_almacen_id = ".$idAlmacen." AND ";
        $SQL .= "    p.eliminado = 0 AND ";
        $SQL .= "    p.material_reparacion = 1 AND ";
        $SQL .= "    p.habilitado = 1 ";
        $SQL .= "ORDER BY ";
        $SQL .= "    titulo ASC, ";
        $SQL .= "    precio_final ASC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
                
        return $stmt->fetchAll();
    }

    public function eliminarReglasProducto($idProducto){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "DELETE FROM reglas_precios_productos WHERE producto_id = ".$idProducto;

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
    }
}
