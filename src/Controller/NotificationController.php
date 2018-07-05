<?php

namespace App\Controller;

use App\Entity\{ Friend, Action };
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Flex\Response;

// The cachers of these are in the navbar.js
class NotificationController extends Controller
{
    /**
     * @Route("/notification/{userId}", name="notification", methods={ "POST" })
     */
    public function notificationUpdater($userId)
    {
        $actions = $this->getDoctrine()
             ->getRepository(Action::class)
             ->findBy([
                 'to_user_id' => $userId,
                 'seen_by_user' => null
             ], [ 'action_date' => 'DESC' ]);

        // Get all friend request sent to this user
        $con = $this->getDoctrine()->getManager()->getConnection();
        $friendsQuery = $con->prepare("SELECT * FROM friend 
                                    LEFT JOIN user 
                                    ON friend.user1_id = user.user_id
                                    WHERE friend.status = 'request'
                                    AND friend.user2_id = :user_id");
        $friendsQuery->execute([ 'user_id' => $userId ]);
        $friendRequests = $friendsQuery->fetchAll();

        if (isset($actions)) {
            $responseJson = [
                'friend' => [],
                'message' => [],
                'general' => [],
                'counters' => [ 'friend' => 0, 'message' => 0, 'general' => 0 ]
            ];

            if (isset($friendRequests)) {
                foreach ($friendRequests as $fr) {
                    $responseJson['counters']['friend']++;
                    $responseJson['friend'][] = [
                        'link' => $this->generateUrl('view_user', [ 'permalink' => $fr['permalink'] ]),
                        'pic' => $fr['profile_pic'],
                        'name' => $fr['first_name'] . ' ' . $fr['last_name']
                    ];
                }
            }

            foreach ($actions as $action) {
                if ($action->getActionType() === 'message') {
                    $responseJson['counters']['message']++;
                }
                if ($action->getActionType() === 'upvote' or $action->getActionType() === 'comment') {
                    $responseJson['counters']['general']++;
                    $responseJson['general'][] = [
                        'link' => $this->generateUrl('view_post', [ 'postId' => $action->getEntityId() ]),
                        'when' => $action->getActionDate()->diff(new \DateTime()),
                        'what' => $action->getActionType()
                    ];
                }
            }
            return new JsonResponse($responseJson);
        }
        return new JsonResponse([]);
    }
}
