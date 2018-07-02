<?php

namespace App\Controller;

use App\Form\UserSignUpType;
use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\{ AuthenticationUtils };

class MainController extends Controller 
{
   /**
    * @Route("/signup", name="signup", methods={ "GET", "POST" })
    */
   public function signup(Request $request, UserPasswordEncoderInterface $passwordEncoder)
   {
      $user = new User();
      $form = $this->createForm(UserSignUpType::class, $user, [
            'action' => $this->generateUrl('signup')
      ]);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPermalink(explode('@', $user->getEmail())[0] . rand(0, 100));
            
            // Save user to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('index');
      }

      return $this->render('main/signup.html.twig', [
            'signup' => $form->createView()
      ]);
   }

   /**
    * @Route("/login", name="login", methods={ "GET", "POST" })
    */
   public function login(Request $request, AuthenticationUtils $au)
   {
      $lastUsername = $au->getLastUsername();
      $error = $au->getLastAuthenticationError();
      
      return $this->render('main/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
      ]);
   }

   /**
    * @Route("/logout", name="logout")
    */
   public function logout()
   {}

   /**
    * @Route("/", name="index", methods={ "GET" })
    */
   public function index()
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

      return $this->render('main/index.html.twig', [
         'posts' => $posts
      ]);
   }
}
