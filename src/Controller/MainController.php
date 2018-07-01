<?php

namespace App\Controller;

use App\Form\UserSignUpType;
use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends Controller 
{
   /**
    * @Route("/", name="index", methods={ "GET", "POST" })
    */
   public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
   {
      $user = new User();
      $form = $this->createForm(UserSignUpType::class, $user, [
            'action' => $this->generateUrl('index')
      ]);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
           return new JsonResponse($user);
           // Save user
      }

      return $this->render('main/index.html.twig', [
            'signup' => $form->createView()
      ]);
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

         $posts[$i]->setUploader($uploader->getFirstName() . ' ' . $uploader->getLastName());
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
