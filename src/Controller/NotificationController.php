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
     * @Route("/notification/friends/{userId}", name="notification_friends", methods={ "POST" })
     */
    public function notificationFriends($userId)
    {
        $actions = $this->getDoctrine()
             ->getRepository(Action::class)
             ->findBy([
                 'to_user_id' => $userId,
                 'seen_by_user' => null
             ]);

        if (isset($actions)) {
            $responseJson = [];
            foreach ($actions as $action) {
                $responseJson[] = [
                    'entity_id' => $action->getEntityId(),
                    'entity_type' => $action->getEntityType(),
                    'action_type' => $action->getActionType(),
                    'action_date' => $action->getActionDate()->diff(new \DateTime())
                ];
            }
            return new JsonResponse($responseJson);
        }

        return new JsonResponse([]);
    }

    /**
     * @Route("/notification/messages/{userId}", name="messages", methods={ "POST" })
     */
    public function messages($userId)
    {


        return new JsonResponse([]);
    }

    /**
     * @Route("/notification/general/{userId}", name="notification", methods={ "POST" })
     */
    public function notification($userId)
    {


        return new JsonResponse([]);
    }
}
