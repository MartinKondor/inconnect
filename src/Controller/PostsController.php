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
      // Get the uploader and the post from the database
      $viewPost = $this->getDoctrine()
                     ->getRepository(Post::class)
                     ->findOneBy([ 'post_id' => $postId ]);
      $uploader = $this->getDoctrine()
                   ->getRepository(User::class)
                   ->findOneBy([ 'user_id' => $viewPost->getUserId() ]);
      if (empty($viewPost) or empty($uploader)) throw $this->createNotFoundException('The post does not exists.');

      // Getting the actions
      $actions = $this->getDoctrine()
                  ->getRepository(Action::class)
                  ->findBy([ 'entity_id' => $viewPost->getPostId() ]);

      $upvotes = 0;
      $comments = [];
      foreach ($actions as $action) {
         if ($action->getActionType() === 'comment')
            $comments[] = $action;
         if ($action->getActionType() === 'upvote') {
             $upvotes++;
             if ($action->getUserId() === $this->getUser()->getUserId())
                 $viewPost->setUpvotedByUser(true);
         }
      }

      $viewPost->setUploader($uploader->getFirstName() . ' ' . $uploader->getLastName());
      $viewPost->setUploaderProfilePic($uploader->getProfilePic());
      $viewPost->setUploaderLink($uploader->getPermalink());

      // Set the comments
      if (empty($comments) or $comments === []) {
          $comments = null;
      } else {
           foreach ($comments as $j => $comment) {
               $commentUploader = $this->getDoctrine()
                   ->getRepository(User::class)
                   ->findOneBy([ 'user_id' => $comment->getUserId() ]);
               $comments[$j]->setCommenterLink($commentUploader->getPermalink());
               $comments[$j]->setCommenterProfile($commentUploader->getProfilePic());
               $comments[$j]->setCommenter($commentUploader->getFirstName() . ' ' . $commentUploader->getLastName());
           }
      }

      $viewPost->setComments($comments);
      $viewPost->setUpvotes($upvotes);

      return $this->render('posts/view.html.twig', [
         'viewPost' => $viewPost
      ]);
   }

   /**
    * @Route("/p/new", name="new_post", methods={ "POST" })
    */
   public function newPost(Request $request)
   {
       $user = $this->getUser();

       $viewPost = new Post();
       $viewPost->setUserId($user->getUserId());
       $viewPost->setContent($_POST['postContent']);
       $viewPost->setDateOfUpload(new \DateTime());

       // if (isset($post['image']))

       $em = $this->getDoctrine()->getManager();
       $em->persist($viewPost);
       $em->flush();

       return $this->redirectToRoute('index');
   }
}
