<?php

namespace App\Controller;

use App\Form\{ UserSignUpType, PostType };
use App\Entity\{ ICUser, Post };
use Symfony\Component\Routing\Annotation\Route;
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
      $user = new ICUser();
      $form = $this->createForm(UserSignUpType::class, $user, [
            'action' => $this->generateUrl('signup')
      ]);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPermalink(explode('@', $user->getEmail())[0] . rand(0, 100));
            
            // Save user to the database
            $em = $this->getDoctrine()
                      ->getManager();
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
   public function logout() {}

    /**
     * @Route("/", name="index", methods={ "GET", "POST" })
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()
                  ->getManager();

        $posts = $em->getRepository(Post::class)
                    ->findRelated($user->getUserId());

        // Set up the post with the comments and upvotes
        foreach ($posts as $i => $post) {
            $actions = $em->getRepository(Post::class)
                        ->findPostActions($post['post_id']);

            // Mark long post for template
            if (strlen($post['content']) > 250)
                $posts[$i]['shortContent'] = substr($posts[$i]['content'], 0, 249).' ...';

            $upvotes = 0;
            $comments = null;
            $posts[$i]['isUpvotedByUser'] = false;

            foreach ($actions as $action) {
                if ($action['action_type'] === 'comment') $comments[] = $action;
                if ($action['action_type'] === 'upvote') {
                    $upvotes++;
                    if ($user->getUserId() == $action['user_id'])
                        $posts[$i]['isUpvotedByUser'] = true;
                }
            }
            $posts[$i]['upvotes'] = $upvotes;
            $posts[$i]['comments'] = $comments;
        }

        /**
         * New post form for index page
         */
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('index')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUserId($user->getUserId())
                 ->setDateOfUpload(new \DateTime());

            // If defined save the post's image
            if (!is_null($post->getImage())) {
                $file = $post->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the posts directory
                $file->move($this->getParameter('post_images_directory'), $fileName);
                $post->setImage($fileName);
            }
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('main/index.html.twig', [
            'posts' => $posts,
            'new_post' => $form->createView()
        ]);
    }
}
