<?php

namespace App\Repository;

use App\Entity\GalleryCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GalleryCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method GalleryCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method GalleryCategory[]    findAll()
 * @method GalleryCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GalleryCategory::class);
    }

    // /**
    //  * @return GalleryCategory[] Returns an array of GalleryCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GalleryCategory
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
