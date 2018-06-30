<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller 
{
   /**
    * @Route("/p/{postid}", name="view_post", methods={ "GET" })
    */
   public function viewPost(int $postid)
   {
      $viewPost = $this->getDoctrine()->getRepository(Post::class)
                     ->findOneBy([ 'post_id' => $postid ]);

      if (empty($viewPost))
         throw $this->createNotFoundException('The post does not exists.');

      // Get the uploader of this post
      $uploader = $this->getDoctrine()->getRepository(User::class)
                     ->findOneBy([ 'user_id' => $viewPost->getUserId() ]);

      if (empty($uploader))
         throw $this->createNotFoundException('The uploader of this post does not exists.');

      // Set some properties for the template
      $viewPost->setUploader($uploader->getName());
      $viewPost->setUploaderProfilePic($uploader->getProfilePic());
      $viewPost->setUploaderLink($uploader->getPermalink());

      // Get the actions related to this post
      $actions = $this->getDoctrine()->getRepository(Action::class)
                  ->findBy([ 'entity_id' => $viewPost->getPostId() ]);

      $upvotes = 0;
      $comments = [];
      \array_map(function($action) {
         if ($action->getActionType() === 'comment') $comments[] = $action;
         if ($action->getActionType() === 'upvote') $upvotes++;
      }, $actions);

      if (empty($comments) or $comments === []) $comments = null;
      $viewPost->setComments($comments);
      $viewPost->setUpvotes($upvotes);
      $viewPost->isUpvotedByUser();

      return $this->render('posts/view.html.twig', [
         'viewPost' => $viewPost
      ]);
   }

   /**
    * @Route("/p/new", name="new_post", methods={ "POST" })
    */
   public function newPost() 
   {
      $viewPost = new Post();
      $viewPost->setUserId(1);
      $viewPost->setContent('First post content ever');
      $viewPost->setDateOfUpload(new \DateTime());

      $em = $this->getDoctrine()->getManager();
      $em->persist($viewPost);
      $em->flush();

      return new Response('ok');
   }
}
