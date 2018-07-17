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

       $query = $connection->prepare("SELECT post.post_id, post.user_id, icuser.user_id, post.image, post.content,
                                        icuser.first_name, icuser.last_name, icuser.permalink, post.date_of_upload, icuser.profile_pic
                                        FROM post RIGHT JOIN icuser
                                        ON post.user_id = icuser.user_id
                                        WHERE post.post_id = :post_id
                                        AND post.post_publicity != 'private'");
       $query->execute([ ':post_id' => $postId ]);
       $post = $query->fetch();
       if (empty($post))
           throw $this->createNotFoundException('The post does not exists.');

       $actionQuery = $connection->prepare("SELECT icuser.user_id, icuser.first_name, icuser.last_name, icuser.permalink, 
                                            icuser.profile_pic, `action`.`action_type`, `action`.`action_date`, `action`.`content`
                                            FROM `action` 
                                            RIGHT JOIN icuser
                                            ON `action`.`user_id` = icuser.user_id
                                            WHERE `action`.`entity_id` = :entity_id
                                            AND (`action`.`action_type` = 'comment' OR `action`.`action_type` = 'upvote')
                                            AND `action`.`entity_type` = 'post'");
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

   /**
    * @Route("/p/{postId}/edit", name="edit_post", methods={ "GET" })
    */
   public function editPost($postId)
   {
       $user = $this->getUser();

       $em = $this->getDoctrine()->getManager();
       $connection = $em->getConnection();

       $query = $connection->prepare("SELECT post.post_id, post.user_id, icuser.user_id, post.image, post.content, post.post_publicity,
                                        icuser.first_name, icuser.last_name, icuser.permalink, post.date_of_upload, icuser.profile_pic
                                        FROM post RIGHT JOIN icuser
                                        ON post.user_id = icuser.user_id
                                        WHERE post.post_id = :post_id");
       $query->execute([ ':post_id' => $postId ]);
       $post = $query->fetch();

       if (empty($post))
           throw $this->createNotFoundException('The post does not exists.');
       // See if the user has permission for editing this post
       if ($post['user_id'] != $user->getUserId())
           throw $this->createAccessDeniedException('Access denied for editing this post.');

       return $this->render('posts/edit.html.twig', [
           'post' => $post
       ]);
   }

    /**
     * @Route("/p/{postId}/delete", name="delete_post", methods={ "POST" })
     */
    public function deletePost($postId)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->findOneBy([ 'post_id' => $postId ]);

        if ($post->getUserId() != $user->getUserId())
            throw $this->createAccessDeniedException('Access denied for deleting this post.');

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/p/{postId}/save", name="save_post", methods={ "POST" })
     */
    public function savePost($postId, Request $request)
    {
        $user = $this->getUser();
        $postData = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Post::class)->findOneBy([ 'post_id' => $postId ]);

        if ($post->getUserId() != $user->getUserId())
            throw $this->createAccessDeniedException('Access denied for change this post.');

        $post->setContent($postData['new_post_content']);
        $post->setPostPublicity($postData['new_post_publicity']);

        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('index');
    }
}
