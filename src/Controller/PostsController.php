<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller 
{
   /**
    * @Route("/p/{postid}", name="view_post", methods={ "GET" })
    */
   public function viewPostAction(int $postid)
   {
      $viewPost = $this->getDoctrine()
                     ->getRepository(Post::class)
                     ->findOneBy([ 'post_id' => $postid ]);

      if (empty($viewPost))
         throw $this->createNotFoundException('The post does not exists.');

      // Get the uploader of this post
      $uploader = $this->getDoctrine()
                     ->getRepository(User::class)
                     ->findOneBy([ 'user_id' => $viewPost->getUserId() ]);

      if (empty($uploader))
         throw $this->createNotFoundException('The uploader of this post does not exists.');

      // Set some properties for the template
      $viewPost->setUploader($uploader->getName());
      $viewPost->setUploaderProfilePic($uploader->getProfilePic());
      $viewPost->setUploaderLink($uploader->getPermalink());
      $viewPost->setComments([]);
      $viewPost->setUpvotes('10');

      return $this->render('posts/view.html.twig', [
         'viewPost' => $viewPost
      ]);
   }

   /**
    * @Route("/p/new", name="new_post", methods={ "POST" })
    */
   public function newPostAction() 
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
