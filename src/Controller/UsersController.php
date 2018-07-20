<?php

namespace App\Controller;

use App\Entity\{ ICUser, Friend, Page, Post };

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response };

class UsersController extends Controller
{
    /**
     * @Route("/u/pages", name="user_pages", methods={ "GET" })
     */
    public function userPages(): Response
    {
        $user = $this->getUser();

        return $this->render('users/pages.html.twig', [
            'pages' => $this->getDoctrine()
                ->getManager()
                ->getRepository(Page::class)
                ->findBy([ 'creator_user_id' => $user->getUserId() ])
        ]);
    }

    /**
     * @Route("/u/settings", name="settings", methods={ "GET" })
     */
    public function settings(): Response
    {
        return $this->render('users/settings.html.twig', []);
    }

   /**
    * @Route("/u/{permalink}", name="view_user", methods={ "GET" })
    */
   public function viewUser(string $permalink): Response
   {
      $user = $this->getUser();
      $em = $this->getDoctrine()->getManager();
      $connection = $em->getConnection();

      // Search by the permalink which can be the user_id or the permalink of a user
      $viewUser = null;
      if (preg_match('/^\d*$/', $permalink)) {
          $viewUser = $this->getDoctrine()
              ->getRepository(ICUser::class)
              ->findOneBy([ 'user_id' => $permalink ]);
      } else {
          $viewUser = $this->getDoctrine()
              ->getRepository(ICUser::class)
              ->findOneBy([ 'permalink' => $permalink ]);
      }
      if (empty($viewUser))
          throw $this->createNotFoundException('The user does not exists.');

      // Getting the friends of the viewed user
      $friendsOfViewedUser = $em->getRepository(Friend::class)
                                ->findFriends($viewUser->getUserId());

      $viewUser->setFriends($friendsOfViewedUser);

      // What is the friend status between the viewed user and the logined in user?
      // From the perspective of the logined in user.
      if (isset($friendsOfViewedUser)) {
          $friendStatus = $this->getDoctrine()
              ->getRepository(Friend::class)
              ->findOneBy([
                  'from_user_id' => $user->getUserId(),
                  'to_user_id' => $viewUser->getUserId()
              ]);
          if (isset($friendStatus)) {
              $viewUser->setFriendStatus($friendStatus->getStatus());
          }
      }

      // Getting the posts
      // If the user is the same as the requested user then show every type of publicity posts
      if ($user->getPermalink() === $permalink) {
          $postsOfViewedUser = $connection->prepare("SELECT * FROM post
                                                LEFT JOIN icuser
                                                ON post.user_id = icuser.user_id
                                                WHERE post.user_id = :user_id
                                                AND post.holder_type = 'user'
                                                ORDER BY post.date_of_upload DESC");
      } else {
          $postsOfViewedUser = $connection->prepare("SELECT * FROM post
                                                LEFT JOIN icuser
                                                ON post.user_id = icuser.user_id
                                                WHERE post.user_id = :user_id
                                                AND post.holder_type = 'user'
                                                AND post.post_publicity != 'private'
                                                ORDER BY post.date_of_upload DESC");
      }
      $postsOfViewedUser->execute([ ':user_id' => $viewUser->getUserId() ]);
      $posts = $postsOfViewedUser->fetchAll();

      // Get the actions of each posts
      foreach ($posts as $i => $post) {
          $actions = $em->getRepository(Post::class)
                        ->findPostActions($post['post_id']);

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
      $viewUser->setPosts($posts);

      return $this->render('users/view.html.twig', [
         'viewUser' => $viewUser
      ]);
   }
}
