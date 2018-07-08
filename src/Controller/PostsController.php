<?php

namespace App\Controller;

use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class PostsController extends Controller 
{
   /**
    * @Route("/p/{postId}", name="view_post", methods={ "GET" })
    */
   public function viewPost($postId)
   {
       $user = $this->getUser();

       $em = $this->getDoctrine()->getManager();
       $connection = $em->getConnection();

       $query = $connection->prepare("SELECT post.post_id, post.user_id, user.user_id, post.content, user.first_name,
                                        user.last_name, user.permalink, post.date_of_upload, user.profile_pic
                                        FROM post RIGHT JOIN user
                                        ON post.user_id = user.user_id
                                        WHERE post.post_id = :post_id");
       $query->execute([ ':post_id' => $postId ]);
       $post = $query->fetch();
       if (empty($post))
           throw $this->createNotFoundException('The post does not exists.');

       $actionQuery = $connection->prepare("SELECT user.user_id, user.first_name, user.last_name, user.permalink, 
                                            user.profile_pic, `action`.`action_type`, `action`.`action_date`, `action`.`content`
                                            FROM `action` 
                                            RIGHT JOIN user
                                            ON `action`.`user_id` = user.user_id
                                            WHERE `action`.`entity_id` = :entity_id");
       $actionQuery->execute([ ':entity_id' => $postId ]);
       $postActions = $actionQuery->fetchAll();

       $upvotes = 0;
       $comments = null;
       $post['isUpvotedByUser'] = false;

       foreach ($postActions as $action) {
           if ($action['action_type'] === 'comment') $comments[] = $action;
           if ($action['action_type'] === 'upvote') {
               $upvotes++;
               if ($user->getUserId() == $action['user_id'])
                   $post['isUpvotedByUser'] = true;
           }
       }

       $post['upvotes'] = $upvotes;
       $post['comments'] = $comments;

       return $this->render('posts/view.html.twig', [
           'post' => $post
       ]);
   }
}
