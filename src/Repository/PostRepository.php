<?php

namespace App\Repository;

use App\Entity\{ Post, ICUser };
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[] Returns an array of Post objects including private ones
     */
    public function findRelated(int $userId)
    {
        // Getting all posts from the friends of the user, and also from the user her/himself
        $query = $this->getEntityManager()
                            ->getConnection()
                            ->prepare("(SELECT post.post_id, post.user_id, icuser.user_id,
                                            post.content, post.image, post.holder_type, 
                                            post.post_publicity, icuser.first_name,
                                            icuser.last_name, icuser.permalink,
                                            post.date_of_upload, icuser.profile_pic 
                                        FROM icuser
                                        INNER JOIN friend
                                        ON friend.to_user_id = icuser.user_id
                                        INNER JOIN post
                                        ON post.user_id = friend.to_user_id
                                        WHERE friend.from_user_id = :user_id
                                        AND friend.status = 'friends'
                                        AND post.content IS NOT NULL
                                        AND post.holder_type != 'page'
                                        AND post.post_publicity = 'public'
                                        OR post.user_id = :user_id
                                        GROUP BY post.post_id, icuser.user_id)
                                        ORDER BY post.date_of_upload DESC
                                        LIMIT 50");
        $query->execute([
            ':user_id' => $userId
        ]);
        return $query->fetchAll();
    }

    /**
     * @return Action[] Returns an array of actions related to the post
     */
    public function findPostActions(int $postId)
    {
        $postQuery = $this->getEntityManager()
                                    ->getConnection()
                                    ->prepare("SELECT icuser.user_id, icuser.first_name, icuser.last_name,
                                                  icuser.permalink, icuser.profile_pic, `action`.action_type,
                                                  `action`.action_date, `action`.content
                                                FROM `action`
                                                RIGHT JOIN icuser
                                                ON `action`.user_id = icuser.user_id
                                                WHERE `action`.entity_id = :entity_id
                                                AND (`action`.action_type = 'comment' OR `action`.action_type = 'upvote')
                                                AND `action`.entity_type = 'post'
                                                ORDER BY `action`.action_date ASC");
        $postQuery->execute([
            ':entity_id' => $postId
        ]);
        return $postQuery->fetchAll();
    }

    /**
     * @return Post Returns a not private post
     */
    public function findById(int $postId)
    {
        $query = $this->getEntityManager()
                            ->getConnection()
                            ->prepare("SELECT post.post_id, post.user_id, icuser.user_id, post.image, post.content,
                                        icuser.first_name, icuser.last_name, icuser.permalink, post.date_of_upload, icuser.profile_pic
                                        FROM post
                                        RIGHT JOIN icuser
                                        ON post.user_id = icuser.user_id
                                        WHERE post.post_id = :post_id
                                        AND post.post_publicity != 'private'");
        $query->execute([
            ':post_id' => $postId
        ]);
        return $query->fetch();
    }

    /**
     * @return Action[] Returns an array of actions related to the post
     */
    public function findUserPosts()
    {}


    /*
    public function findOneBySomeField($value): ?Post
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
