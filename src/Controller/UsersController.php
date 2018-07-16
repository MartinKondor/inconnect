<?php

namespace App\Controller;

use App\Entity\{ ICUser, Friend, Page };

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller 
{
   /**
    * @Route("/u/{permalink}", name="view_user", methods={ "GET" })
    */
   public function viewUser($permalink)
   {
      $user = $this->getUser();

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
      $em = $this->getDoctrine()->getManager();
      $connection = $em->getConnection();
      $friendQuery = $connection->prepare("SELECT * FROM friend
                                            RIGHT JOIN icuser
                                            ON friend.to_user_id = icuser.user_id
                                            WHERE friend.from_user_id = :from_user_id AND friend.status = 'friends'");
      $friendQuery->execute([ ':from_user_id' => $viewUser->getUserId() ]);
      $friendsOfViewedUser = $friendQuery->fetchAll();
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
          $postQuery = $connection->prepare("SELECT icuser.user_id, icuser.first_name, icuser.last_name, icuser.permalink, 
                                                icuser.profile_pic, \"action\".action_type, \"action\".action_date, \"action\".content
                                                FROM \"action\" RIGHT JOIN icuser
                                                ON \"action\".user_id = icuser.user_id
                                                WHERE \"action\".entity_id = :entity_id
                                                AND (\"action\".action_type = 'comment' OR \"action\".action_type = 'upvote')
                                                AND \"action\".entity_type = 'post'");
          $postQuery->execute([
              ':entity_id' => $post['post_id']
          ]);
          $actions = $postQuery->fetchAll();

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

    /**
     * @Route("/user/pages", name="user_pages", methods={ "GET" })
     */
    public function userPages()
    {
        $user = $this->getUser();

        $pages = $this->getDoctrine()
                    ->getManager()
                    ->getRepository(Page::class)
                    ->findBy([ 'creator_user_id' => $user->getUserId() ]);

        return $this->render('users/pages.html.twig', [
            'pages' => $pages
        ]);
    }

   /**
    * @Route("/user/settings", name="settings", methods={ "GET" })
    */
   public function settings()
   {
        return $this->render('users/settings.html.twig', []);
   }
}
