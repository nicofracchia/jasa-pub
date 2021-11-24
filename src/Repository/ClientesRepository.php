<?php

namespace App\Repository;

use App\Entity\Clientes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Clientes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clientes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clientes[]    findAll()
 * @method Clientes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clientes::class);
    }

    /**
     * @return Clientes[] Returns an array of Clientes objects
     */
    public function findAllVigentes()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.eliminado = :val')
            ->setParameter('val', 0)
            ->orderBy('c.apellido', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllBusqueda($busqueda){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT * FROM clientes ";
        $SQL .= "WHERE ";
        foreach($busqueda as $b){
            $SQL .= "    (razon_social LIKE '%".$b."%' OR cuit LIKE '%".$b."%' OR direccion LIKE '%".$b."%' OR nombre LIKE '%".$b."%' OR apellido LIKE '%".$b."%' OR mail LIKE '%".$b."%' OR dni LIKE '%".$b."%') AND ";
        }
        $SQL .= "    eliminado = 0 AND ";
        $SQL .= "    habilitado = 1 ";
        $SQL .= "ORDER BY ";
        $SQL .= "    apellido ASC, ";
        $SQL .= "    nombre ASC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        
        return $stmt->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Clientes
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
