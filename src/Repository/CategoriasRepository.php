<?php

namespace App\Repository;

use App\Entity\Categorias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categorias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorias[]    findAll()
 * @method Categorias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorias::class);
    }

    /**
     * @return Categorias[] Returns an array of Categorias objects
     */
    
    public function findAllFinal()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.final = :val')
            ->setParameter('val', 1)
            ->orderBy('c.nombre', 'ASC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function eliminarArbol($id){
        $conn = $this->getEntityManager()->getConnection();

        $SQL  = "UPDATE categorias SET eliminado = 1 WHERE id = ".$id." OR grupo_id LIKE '%".$id."%'";

        $stmt = $conn->prepare($SQL);
        $stmt->execute();
        
        return $id;
    }
    

    /*
    public function findOneBySomeField($value): ?Categorias
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
