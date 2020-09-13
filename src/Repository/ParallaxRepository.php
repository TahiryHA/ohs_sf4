<?php

namespace App\Repository;

use App\Entity\Parallax;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Parallax|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parallax|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parallax[]    findAll()
 * @method Parallax[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParallaxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parallax::class);
    }

    // /**
    //  * @return Parallax[] Returns an array of Parallax objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Parallax
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
