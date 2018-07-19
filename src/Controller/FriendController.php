<?php

namespace App\Controller;

use App\Entity\Friend;
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
        // because if the requested User2 already added
        // Logined User as a friend then this friend adding equals to a friend request acceptance
        $user2Request = $em->getRepository(Friend::class)
                            ->findOneBy([
                                'from_user_id' => $user2Id,
                                'to_user_id' => $user->getUserId()
                            ]);
        if (isset($user2Request)) {
            if ($user2Request->getStatus() === 'request' || $user2Request->getStatus() === 'muted_request') {
                // Then save a connection on this two user as friends

                $user2Request->setStatus('friends');
                $em->flush();

                $fr = new Friend();
                $fr->setFromUserId($user->getUserId())
                    ->setToUserId($user2Id)
                    ->setStatus('friends');
                $em->persist($fr);
                $em->flush();

                return new Response('success');
            }
        }

        $fr = new Friend();
        $fr->setFromUserId($user->getUserId())
            ->setToUserId($user2Id)
            ->setStatus($_POST['isMuted']);
        $em->persist($fr);
        $em->flush();

        return new Response('success');
    }

    /**
     * @Route("/friend/request/accept/{userId}", name="friend_request_accept", methods={ "POST" })
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
        // Update the 'friend' request to 'friends'
        $friendRequest->setStatus('friends');
        $em->flush();

        $fr = new Friend();
        $fr->setFromUserId($user->getUserId())
            ->setToUserId($userId)
            ->setStatus('friends');
        $em->persist($fr);
        $em->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/friend/request/remove/{userId}", name="remove_friend_request", methods={ "POST" })
     */
    public function removeFriendRequest($userId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $friendRequest = $em->getRepository(Friend::class)
            ->findOneBy([
                'from_user_id' => $user->getUserId(),
                'to_user_id' => $userId
            ]);
        $em->remove($friendRequest);
        $em->flush();

        return new Response('success');
    }

    /**
     * @Route("/friend/request/decline/{userId}", name="friend_request_decline", methods={ "POST" })
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