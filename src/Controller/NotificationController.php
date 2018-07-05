<?php

namespace App\Controller;

use App\Entity\Action;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        if (isset($actions)) {
            $responseJson = [
                'friend' => [],
                'message' => [],
                'general' => [],
                'counters' => [ 'friend' => 0, 'message' => 0, 'general' => 0 ]
            ];
            foreach ($actions as $action) {
                if ($action->getActionType() === 'friend') {
                    $responseJson['counters']['friend']++;
                }
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
