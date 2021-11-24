<?php

namespace App\Repository;

use App\Entity\Cotizaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cotizaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cotizaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cotizaciones[]    findAll()
 * @method Cotizaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CotizacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cotizaciones::class);
    }

    public function alertas(){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT  ";
        $SQL .= "    c.fecha, c.hasta, c.descuento, c.mantener_precio,  ";
        $SQL .= "    a.nombre AS almacen,  ";
        $SQL .= "    CONCAT(cl.apellido, ', ', cl.nombre) AS cliente,  ";
        $SQL .= "    CONCAT(u.nombre, ' ', u.apellido) AS creador,  ";
        $SQL .= "    SUM(p.costo) AS costo, SUM(p.precio_final) AS precio ";
        $SQL .= "FROM cotizaciones AS c ";
        $SQL .= "INNER JOIN almacenes AS a ";
        $SQL .= "    ON c.almacen_id = a.id ";
        $SQL .= "INNER JOIN clientes AS cl ";
        $SQL .= "    ON c.id_cliente_id = cl.id ";
        $SQL .= "INNER JOIN usuarios AS u ";
        $SQL .= "    ON c.creador_id = u.id ";
        $SQL .= "INNER JOIN cotizaciones_productos AS cp ";
        $SQL .= "    ON cp.id_cotizacion_id = c.id ";
        $SQL .= "INNER JOIN productos AS p ";
        $SQL .= "    ON cp.id_producto_id = p.id ";
        $SQL .= "WHERE DATE_ADD(NOW(), INTERVAL 2 DAY) > c.hasta AND estado = 'Espera respuesta' ";
        $SQL .= "ORDER BY hasta ASC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // /**
    //  * @return Cotizaciones[] Returns an array of Cotizaciones objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cotizaciones
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
