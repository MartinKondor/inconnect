<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return Message[] Returns an array of message objects
     */
    public function findMessages($userId, $fromUserId)
    {
        $msgQuery = $this->getEntityManager()
            ->getConnection()
            ->prepare("SELECT message.to_user_id, message.from_user_id, message.content, message.send_date, message.status
                       FROM message
                       LEFT JOIN icuser
                       ON message.to_user_id = icuser.user_id
                       WHERE icuser.user_id = :user_id
                       AND (message.to_user_id = :user_id OR message.from_user_id = :from_user_id)
                       OR (message.to_user_id = :from_user_id OR message.from_user_id = :user_id)");
        $msgQuery->execute([
            ':user_id' => $userId,
            ':from_user_id' => $fromUserId
        ]);
        return $msgQuery->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Message
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
