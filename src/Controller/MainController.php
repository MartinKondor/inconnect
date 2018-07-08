<?php

namespace App\Controller;

use App\Form\{ UserSignUpType, PostType };
use App\Entity\{ User, Post, Action };

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
   public function logout() {}

    /**
     * @Route("/", name="index", methods={ "GET", "POST" })
     */
    public function index(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();

        // Getting all posts from the friends of the user, and also from the user her/himself
        $query = $connection->prepare("(SELECT post.post_id, post.user_id, user.user_id, post.content, post.image, 
                                        user.first_name, user.last_name, user.permalink, post.date_of_upload, user.profile_pic 
                                        FROM user
                                        INNER JOIN friend
                                        ON friend.to_user_id = user.user_id
                                        INNER JOIN post
                                        ON post.user_id = friend.to_user_id
                                        WHERE friend.from_user_id = :user_id
                                        AND friend.status = 'friends'
                                        AND post.content IS NOT NULL
                                        OR post.user_id = :user_id
                                        GROUP BY post.post_id)
                                        ORDER BY post.date_of_upload DESC
                                        LIMIT 50");
        $query->execute([ ':user_id' => $this->getUser()->getUserId() ]);
        $posts = $query->fetchAll();

        // Set up the post with the comments and upvotes
        foreach ($posts as $i => $post) {
            $postQuery = $connection->prepare("SELECT user.user_id, user.first_name, user.last_name, user.permalink, 
                                                user.profile_pic, `action`.`action_type`, `action`.`action_date`, `action`.`content`
                                                FROM `action` RIGHT JOIN user
                                                ON `action`.`user_id` = user.user_id
                                                WHERE `action`.`entity_id` = :entity_id");
            $postQuery->execute([
                ':entity_id' => $post['post_id']
            ]);
            $actions = $postQuery->fetchAll();

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
         * New post form
         */
        // Create form
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, [
            'action' => $this->generateUrl('index')
        ]);
        $form->handleRequest($request);

        // Handle new post request
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $post->setUserId($user->getUserId());
            $post->setDateOfUpload(new \DateTime());

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
