<?php

namespace App\Repository;

use App\Entity\PagePost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PagePost|null find($id, $lockMode = null, $lockVersion = null)
 * @method PagePost|null findOneBy(array $criteria, array $orderBy = null)
 * @method PagePost[]    findAll()
 * @method PagePost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PagePostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PagePost::class);
    }

//    /**
//     * @return PagePost[] Returns an array of PagePost objects
//     */
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
    public function findOneBySomeField($value): ?PagePost
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
