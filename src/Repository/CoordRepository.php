<?php

namespace App\Repository;

use App\Entity\Coord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Coord|null find($id, $lockMode = null, $lockVersion = null)
 * @method Coord|null findOneBy(array $criteria, array $orderBy = null)
 * @method Coord[]    findAll()
 * @method Coord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coord::class);
    }

    // /**
    //  * @return Coord[] Returns an array of Coord objects
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
    public function findOneBySomeField($value): ?Coord
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
