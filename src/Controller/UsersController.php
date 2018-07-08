<?php

namespace App\Controller;

use App\Entity\{ User, Friend, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UsersController extends Controller 
{
   /**
    * @Route("/u/{permalink}", name="view_user", methods={ "GET" })
    */
   public function viewUser($permalink)
   {
      $user = $this->getUser();

      $viewUser = null;
      if (preg_match('/^\d*$/', $permalink)) {
          $viewUser = $this->getDoctrine()
              ->getRepository(User::class)
              ->findOneBy([ 'user_id' => $permalink ]);
      } else {
          $viewUser = $this->getDoctrine()
              ->getRepository(User::class)
              ->findOneBy([ 'permalink' => $permalink ]);
      }
      if (empty($viewUser) || $viewUser == null)
          throw $this->createNotFoundException('The user does not exists.');

      // Getting the friends of the viewed user
      $em = $this->getDoctrine()->getManager();
      $connection = $em->getConnection();
      $friendQuery = $connection->prepare("SELECT * FROM friend
                                            RIGHT JOIN user
                                            ON friend.to_user_id = user.user_id
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
      $postsOfViewedUser = $connection->prepare("SELECT * FROM post
                                                LEFT JOIN user
                                                ON post.user_id = user.user_id
                                                WHERE post.user_id = :user_id
                                                ORDER BY post.date_of_upload DESC");
      $postsOfViewedUser->execute([ ':user_id' => $viewUser->getUserId() ]);
      $posts = $postsOfViewedUser->fetchAll();

      // Get the actions of each posts
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
    * @Route("/info/settings", name="settings", methods={ "GET" })
    */
   public function settings()
   {
        return $this->render('users/settings.html.twig', []);
   }
}
