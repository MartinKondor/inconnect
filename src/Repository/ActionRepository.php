<?php

namespace App\Repository;

use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Action::class);
    }


    /**
     * @return Action[] Returns an array of Action: type="message" objects
     */
    public function findMessages($userId, $fromUserId)
    {
        $msgQuery = $this->getEntityManager()
                            ->getConnection()
                            ->prepare("SELECT icuser.user_id, `action`.`action_type`, `action`.`entity_type`,
                                        `action`.`user_id`, `action`.`content`, `action`.`to_user_id`,
                                        `action`.`action_date`, icuser.first_name,
                                        icuser.last_name
                                        FROM `action`
                                        LEFT JOIN icuser
                                        ON icuser.user_id = `action`.`user_id`
                                        WHERE `action`.`entity_type` = 'message'
                                        AND ((`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id) OR (`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id))
                                        OR (`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id)
                                        OR (`action`.`to_user_id` = :from_user_id AND `action`.`user_id` = :user_id)
                                        AND `action`.`action_type` = 'message'
                                        ORDER BY `action`.action_date ASC");
        $msgQuery->execute([
            ':user_id' => $userId,
            ':from_user_id' => $fromUserId
        ]);
        return $msgQuery->fetchAll();
    }

    /*
    public function findOneBySomeField($value): ?Action
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
