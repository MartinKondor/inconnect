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
   public function viewUser(string $permalink)
   {
      $user = $this->getUser();

      $viewUser = $this->getDoctrine()
                  ->getRepository(User::class)
                  ->findOneBy([ 'permalink' => $permalink ]);
      if (empty($viewUser))
          throw $this->createNotFoundException('The user does not exists.');

      // Getting the friends of the viewed user
      $em = $this->getDoctrine()->getManager();
      $connection = $em->getConnection();
      $friendQuery = $connection->prepare("SELECT * FROM friend
                                            RIGHT JOIN user
                                            ON friend.user2_id = user.user_id
                                            WHERE friend.user1_id = 24 AND friend.status = 'friend'");
      $friendQuery->execute([ ':user1_id' => $viewUser->getUserId() ]);
      $friendsOfViewedUser = $friendQuery->fetchAll();
      $viewUser->setFriends($friendsOfViewedUser);

      // What is the friend status between the viewed user and the logined in user?
      // From the perspective of the logined in user.
      if (isset($friendsOfViewedUser)) {
          $friendStatus = $this->getDoctrine()
              ->getRepository(Friend::class)
              ->findOneBy([
                  'user1_id' => $user->getUserId(),
                  'user2_id' => $viewUser->getUserId()
              ]);
          if (isset($friendStatus)) {
              $viewUser->setFriendStatus($friendStatus->getStatus());
          }
      }

      // Getting the posts
      $postsOfViewedUser = $connection->prepare("SELECT * FROM post
                                                LEFT JOIN user
                                                ON post.user_id = user.user_id
                                                WHERE post.user_id = :user_id");
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
     * @Route("/u/new/{user2Id}", name="add_friend", methods={ "POST" })
     */
   public function addFriend($user2Id)
   {
        // Create a new friend request
        $user = $this->getUser();
        $requestedUser = $this->getDoctrine()
                        ->getRepository(User::class)
                        ->findOneBy([ 'user_id' => $user2Id ]);
        if (empty($requestedUser))
            return new Response('fail');

        $em = $this->getDoctrine()
                        ->getManager();

        $fr = new Friend();
        $fr->setUser1Id($user->getUserId());
        $fr->setUser2Id($requestedUser->getUserId());
        $fr->setStatus('request');

        $em->persist($fr);
        $em->flush();
        return new Response('success');
   }
}
