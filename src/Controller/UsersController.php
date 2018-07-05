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

      // Posts section
      $postsOfViewedUser = $this->getDoctrine()
                                ->getRepository(Post::class)
                                ->findBy([ 'user_id' => $viewUser->getUserId() ],
                                         [ 'date_of_upload' => 'DESC' ]);

      // Setting up posts of user
      foreach ($postsOfViewedUser as $i => $post) {
          $actions = $this->getDoctrine()
                      ->getRepository(Action::class)
                      ->findBy([ 'entity_id' => $post->getPostId() ]);

          $upvotes = 0;
          $comments = null;
          foreach ($actions as $j => $action) {
              if ($action->getActionType() === 'upvote') $upvotes++;
              if ($action->getActionType() === 'comment') {

                 // Setting up comments
                 $commentUploader = $this->getDoctrine()
                      ->getRepository(User::class)
                      ->findOneBy([ 'user_id' => $actions[$j]->getUserId() ]);

                 $actions[$j]->setCommenterLink($commentUploader->getPermalink());
                 $actions[$j]->setCommenterProfile($commentUploader->getProfilePic());
                 $actions[$j]->setCommenter($commentUploader->getFirstName() . ' ' . $commentUploader->getLastName());
                 $comments[] = $actions[$j];
              }
          }

          $postsOfViewedUser[$i]->setUploader($viewUser->getFirstName() . ' ' . $viewUser->getLastName());
          $postsOfViewedUser[$i]->setUploaderLink($viewUser->getPermalink());
          $postsOfViewedUser[$i]->setUploaderProfilePic($viewUser->getProfilePic());
          $postsOfViewedUser[$i]->setUpvotes($upvotes);
          $postsOfViewedUser[$i]->setComments($comments);
      }

      $viewUser->setPosts($postsOfViewedUser);


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
