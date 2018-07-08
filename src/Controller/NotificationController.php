<?php

namespace App\Controller;

use App\Entity\{ Action };
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ JsonResponse, Response };
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends Controller
{
    /**
     * @Route("/notification", name="notification", methods={ "POST" })
     */
    public function notificationUpdater()
    {
        $user = $this->getUser();
        $userId = $user->getUserId();

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
                                    ON friend.from_user_id = user.user_id
                                    WHERE friend.status = 'request'
                                    AND friend.to_user_id = :to_user_id");
        $friendsQuery->execute([ ':to_user_id' => $userId ]);
        $friendRequests = $friendsQuery->fetchAll();

        if (isset($actions)) {
            $responseJson = [
                'friend' => [],
                'message' => [],
                'general' => [],
                'counters' => [ 'friend' => 0, 'message' => 0, 'general' => 0 ]
            ];

            if (isset($friendRequests)) {
                foreach ($friendRequests as $friend) {
                    $responseJson['counters']['friend']++;
                    $responseJson['friend'][] = [
                        'link' => $this->generateUrl('view_user', [ 'permalink' => $friend['permalink'] ]),
                        'pic' => $friend['profile_pic'],
                        'name' => $friend['first_name'] . ' ' . $friend['last_name'],
                        'acceptLink' => $this->generateUrl('friend_request_accept', [ 'userId' => $friend['user_id'] ]),
                        'declineLink' => $this->generateUrl('friend_request_decline', [ 'userId' => $friend['user_id'] ])
                    ];
                }
            }

            foreach ($actions as $action) {
                // The event is not from the logined user
                if ($action->getUserId() === $this->getUser()->getUserId()) continue;
                if ($action->getActionType() === 'message') {
                    $responseJson['counters']['message']++;
                }
                if ($action->getActionType() === 'upvote' or $action->getActionType() === 'comment') {
                    $responseJson['counters']['general']++;
                    $responseJson['general'][] = [
                        'deleteLink' => $this->generateUrl('mute_notification', [ 'actionId' => $action->getActionId() ]),
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

    /**
     * @Route("/notification/mute/{actionId}", name="mute_notification", methods={ "POST" })
     */
    public function muteNotification($actionId)
    {
        $em = $this->getDoctrine()
                ->getManager();
        $action = $em->getRepository(Action::class)
                ->findOneBy([ 'action_id' => $actionId ]);
        $action->setSeenByUser('seen');
        $em->flush();
        return $this->redirectToRoute('index');
    }
}
