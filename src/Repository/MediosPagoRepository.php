<?php

namespace App\Repository;

use App\Entity\MediosPago;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediosPago|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediosPago|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediosPago[]    findAll()
 * @method MediosPago[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediosPagoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediosPago::class);
    }

    public function findByClienteCC($CCHabilitado){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "SELECT id, nombre FROM medios_pago ";
        $SQL .= "WHERE ";
        if(!$CCHabilitado){
            $SQL .= "  id != 7 AND ";
        }
        $SQL .= "    eliminado = 0 AND ";
        $SQL .= "    habilitado = 1 ";
        $SQL .= "ORDER BY ";
        $SQL .= "    nombre ASC";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        
        return $stmt->fetchAll();
    }

    // /**
    //  * @return MediosPago[] Returns an array of MediosPago objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediosPago
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
