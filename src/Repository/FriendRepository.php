<?php

namespace App\Repository;

use App\Entity\Friend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Friend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friend[]    findAll()
 * @method Friend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Friend::class);
    }

    /**
     * @return Friend[] Returns an array of Friend objects
     */
    public function findContacts(int $userId)
    {
        $contactsQuery = $this->getEntityManager()
                        ->getConnection()
                        ->prepare("SELECT * FROM friend
                                LEFT JOIN icuser
                                ON friend.to_user_id = icuser.user_id
                                WHERE friend.from_user_id = :user_id
                                AND friend.status = 'friends'");
        $contactsQuery->execute([
            ':user_id' => $userId
        ]);
        return $contactsQuery->fetchAll();
    }

    /**
     * @return Friend[] Returns an array of Friend objects
     */
    public function findFriends(int $userId)
    {
        $friendQuery = $this->getEntityManager()
                                ->getConnection()
                                ->prepare("SELECT * FROM friend
                                            RIGHT JOIN icuser
                                            ON friend.to_user_id = icuser.user_id
                                            WHERE friend.from_user_id = :from_user_id AND friend.status = 'friends'");
        $friendQuery->execute([ ':from_user_id' => $userId ]);
        return $friendQuery->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Friend
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
