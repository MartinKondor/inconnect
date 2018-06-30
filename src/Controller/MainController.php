<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Action;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller 
{
   /**
    * @Route("/", name="index", methods={ "GET" })
    */
   public function index()
   {
      return $this->render('main/index.html.twig', []);
   }

   /**
    * @Route("/home", name="home", methods={ "GET" })
    */
   public function home()
   {
      // Get all posts from the database
      $posts = $this->getDoctrine()
                  ->getRepository(Post::class)
                  ->findAll();

      // Set posts for template
      foreach ($posts as $i => $post) {
         $uploader = $this->getDoctrine()
                  ->getRepository(User::class)
                  ->findOneBy([ 'user_id' => $posts[$i]->getUserId() ]);
         $actions = $this->getDoctrine()
                  ->getRepository(Action::class)
                  ->findBy([ 'entity_id' => $posts[$i]->getPostId() ]);

         $posts[$i]->setUploader($uploader->getName());
         $posts[$i]->setUploaderProfilePic($uploader->getProfilePic());
         $posts[$i]->setUploaderLink($uploader->getPermalink());

         if (isset($actions) and $actions !== []) {
            $upvotes = 0;
            $comments = [];
            \array_map(function($action) {
                  if ($action->getActionType() === 'comment') $comments[] = $action;
                  if ($action->getActionType() === 'upvote') $upvotes++;
            }, $actions);

            if (empty($comments) or $comments === []) $comments = null;
            $posts[$i]->setComments($comments);
            $posts[$i]->setUpvotes($upvotes);
            $posts[$i]->isUpvotedByUser();

         } else {
            $posts[$i]->setComments(null);
            $posts[$i]->setUpvotes(0);
         }
      }

      return $this->render('main/home.html.twig', [
         'posts' => $posts
      ]);
   }
}
