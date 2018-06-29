<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller 
{
   /**
    * @Route("/", name="index", methods={ "GET" })
    */
   public function indexAction()
   {
      return $this->render('main/index.html.twig', [
         
      ]);
   }

   /**
    * @Route("/home", name="home", methods={ "GET" })
    */
   public function homeAction()
   {
      // Only for logined in users
      $posts = [
         [
            'post_id' => 0,
            'uploader_profile_pic' => 'default.png',
            'time' => date('Y-m-d G:i:s'),
            'uploader_link' => 'uploaderLink',
            'uploader' => 'uploader',
            'upvotes' => 0,
            'postUpvotedByUser' => false,
            'content' => 'Content of post',
            'comments' => null
         ],
         [
            'post_id' => 0,
            'uploader_profile_pic' => 'default.png',
            'time' => date('Y-m-d G:i:s'),
            'uploader_link' => 'uploaderLink',
            'uploader' => 'uploader',
            'upvotes' => 0,
            'postUpvotedByUser' => false,
            'content' => 'Content of post',
            'comments' => null
         ],
         [
            'post_id' => 0,
            'uploader_profile_pic' => 'default.png',
            'time' => date('Y-m-d G:i:s'),
            'uploader_link' => 'uploaderLink',
            'uploader' => 'uploader',
            'upvotes' => 0,
            'postUpvotedByUser' => false,
            'content' => 'Content of post',
            'comments' => null
         ],
         [
            'post_id' => 0,
            'uploader_profile_pic' => 'default.png',
            'time' => date('Y-m-d G:i:s'),
            'uploader_link' => 'uploaderLink',
            'uploader' => 'uploader',
            'upvotes' => 0,
            'postUpvotedByUser' => false,
            'content' => 'Content of post',
            'comments' => null
         ]
      ];

      return $this->render('main/home.html.twig', [
         'posts' => $posts
      ]);
   }
}
