<?php

namespace App\Controller;

use App\Entity\{ User, Post, Action };

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class ActionController extends Controller
{
    /**
     * @Route("/action/upvote/{entityId}/{toUserId}", name="upvote", methods={ "POST" })
     */
    public function upvote($entityId, $toUserId, Request $request)
    {
        $user = $this->getUser();

        $actions = $this->getDoctrine()
                        ->getManager()
                        ->getRepository(Action::class)
                        ->findBy([
                            'entity_id' => $entityId,
                            'action_type' => 'upvote'
                        ]);

        $upvoteCount = 0;
        $em = $this->getDoctrine()->getManager();

        if (isset($actions)) {
            $upvoteCount = count($actions);

            // If user upvoted post, delete the user realated upvote
            // action from database & send back "down-<upvotes>"
            // else save it and response "up-<upvotes>"
            foreach ($actions as $a) {
                if ($a->getUserId() === $user->getUserId()) {

                    // Delete user upvote from database
                    $em->remove($a);
                    $em->flush();

                    // The ternary op. is to pervent upvotes showing up like -1
                    return new Response("down-" . ($upvoteCount - 1));
                }
            }
        }

        // Insert user upvote in database
        $userAction = new Action();
        $userAction->setEntityId($entityId);
        $userAction->setUserId($user->getUserId());
        $userAction->setToUserId($toUserId);
        $userAction->setActionDate(new \DateTime());
        $userAction->setEntityType('post');
        $userAction->setActionType('upvote');

        $em->persist($userAction);
        $em->flush();

        return new Response("up-" . ($upvoteCount + 1));
    }

    /**
     * @Route("/action/comment/{entityId}/{toUserId}", name="comment", methods={ "POST" })
     */
    public function comment($entityId, $toUserId)
    {
        if (empty($_POST['comment'])) return new Response('failure');

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $userAction = new Action();
        $userAction->setEntityId($entityId);
        $userAction->setUserId($user->getUserId());
        $userAction->setToUserId($toUserId);
        $userAction->setActionDate(new \DateTime());
        $userAction->setEntityType('post');
        $userAction->setActionType('comment');
        $userAction->setContent($_POST['comment']);

        $em->persist($userAction);
        $em->flush();

        return new Response('success');
    }
}