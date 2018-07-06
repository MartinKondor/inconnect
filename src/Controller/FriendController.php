<?php

namespace App\Controller;

use App\Entity\{ User, Friend, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FriendController extends Controller
{
    /**
     * @Route("/friend/add/{user2Id}", name="add_friend", methods={ "POST" })
     */
    public function addFriend($user2Id)
    {
        // Create a new friend request
        $user = $this->getUser();
        $em = $this->getDoctrine()
            ->getManager();

        // First, see the connections between the two user
        // beacause if the requested User2 already added
        // Logined User as a friend then this friend adding equals to a friend request acception
        $user2Request = $em->getRepository(Friend::class)
                            ->findOneBy([
                                'from_user_id' => $user2Id,
                                'to_user_id' => $user->getUserId()
                            ]);
        if (isset($user2Request)) {
            if ($user2Request->getStatus() === 'request') {
                // Then save a connection on this two user as friends

                $user2Request->setStatus('friends');
                $em->flush();

                $fr = new Friend();
                $fr->setFromUserId($user->getUserId());
                $fr->setToUserId($user2Id);
                $fr->setStatus('friends');
                $em->persist($fr);
                $em->flush();

                return new Response('success');
            }
        }

        $fr = new Friend();
        $fr->setFromUserId($user->getUserId());
        $fr->setToUserId($user2Id);
        $fr->setStatus('request');
        $em->persist($fr);
        $em->flush();
        return new Response('success');
    }

    /**
     * @Route("/friend/accept/{userId}", name="friend_request_accept", methods={ "POST" })
     */
    public function acceptFriend($userId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository(Friend::class)
            ->findOneBy([
                'from_user_id' => $userId,
                'to_user_id' => $user->getUserId()
            ]);
        $friendRequest->setStatus('friends');
        $em->flush();

        $fr = new Friend();
        $fr->setFromUserId($user->getUserId());
        $fr->setToUserId($userId);
        $fr->setStatus('friends');
        $em->persist($fr);
        $em->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/friend/decline/{userId}", name="friend_request_decline", methods={ "POST" })
     */
    public function declineFriend($userId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository(Friend::class)
            ->findOneBy([
                'from_user_id' => $userId,
                'to_user_id' => $user->getUserId()
            ]);
        $em->remove($friendRequest);
        $em->flush();

        return $this->redirectToRoute('index');
    }
}