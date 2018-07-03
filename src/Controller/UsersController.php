<?php

namespace App\Controller;

use App\Entity\{ User, Friend };

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

      $friends = $this->getDoctrine()
                  ->getRepository(Friend::class)
                  ->findBy([ 'user1_id' => $viewUser->getUserId() ]);

      $viewUser->setFriends($friends);

      // The current user sent the request?
      $viewUser->setFriendRequestSent(false);
      foreach ($friends as $friend) {
          if ($friend->getUser1Id() === $user->getUserId() or $friend->getUser2Id() === $user->getUserId()) {
              if ($friend->getStatus() === 'request')
                  $viewUser->setFriendRequestSent(true);
              elseif ($friend->getStatus() === 'friends')
                  $viewUser->setFriendOfUser(true);
          }
      }

      if (empty($viewUser))
         throw $this->createNotFoundException('The user does not exists.');

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
        return new Response('success');
   }
}
