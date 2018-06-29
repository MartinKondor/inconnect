<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PostsController extends Controller 
{
   /**
    * @Route("/p/{postid}", name="view_post", methods={ "GET" })
    */
   public function viewPostAction(string $postid)
   {

      $viewPost = [
         'post_id' => 0,
         'uploader_profile_pic' => 'default.png',
         'time' => date('Y-m-d G:i:s'),
         'uploader_link' => 'uploaderLink',
         'uploader' => 'uploader',
         'upvotes' => 0,
         'postUpvotedByUser' => false,
         'content' => 'Content of post',
         'comments' => null
      ];

      return $this->render('posts/view.html.twig', [
         'viewPost' => $viewPost
      ]);
   }
}
