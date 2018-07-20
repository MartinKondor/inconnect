<?php

namespace App\Repository;

use App\Entity\ICUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ICUser::class);
    }

    /**
     * @return User[] Returns an array of ICUser objects
     */
    public function findByName(string $fullName)
    {
        $searchQuery = $this->getEntityManager()
                ->getConnection()
                ->prepare("SELECT profile_pic, permalink, first_name, last_name
                          FROM icuser
                          WHERE first_name LIKE :queryFirst OR 
                          last_name LIKE :queryLast LIMIT 5;");

        // If the search contains first and last name split it for the query
        if (preg_match('/.*\s{1}.*/', $fullName)) {
            list($queryFirst, $queryLast) = explode(' ', $fullName);
            $searchQuery->execute([
                ':queryFirst' => "%$queryFirst%",
                ':queryLast' => "%$queryLast%"
            ]);
        } else {
            $searchQuery->execute([
                ':queryFirst' => "%{$fullName}%",
                ':queryLast' => "%{$fullName}%"
            ]);
        }

        return $searchQuery->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
