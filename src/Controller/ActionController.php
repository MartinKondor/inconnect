<?php

namespace App\Controller;

use App\Entity\Action;
use PHPUnit\Util\Json;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class ActionController extends Controller
{
    /**
     * @Route("/action/upvote/{entityId}/{toUserId}", name="upvote", methods={ "POST" })
     */
    public function upvote(int $entityId, int $toUserId): JsonResponse
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

            // If user upvoted the post, delete the user related upvote
            // action from database else save it
            foreach ($actions as $a) {
                if ($a->getUserId() === $user->getUserId()) {

                    // Delete user upvote from database
                    $em->remove($a);
                    $em->flush();

                    return new JsonResponse([
                        'way' => 'down',
                        'upvoteCount' => ($upvoteCount - 1)
                    ]);
                }
            }
        }

        // Insert user upvote in database
        $userAction = new Action();
        $userAction->setEntityId($entityId)
                ->setUserId($user->getUserId())
                ->setToUserId($toUserId)
                ->setActionDate(new \DateTime())
                ->setEntityType('post')
                ->setActionType('upvote');
        $em->persist($userAction);
        $em->flush();

        return new JsonResponse([
            'way' => 'up',
            'upvoteCount' => ($upvoteCount + 1) // Prevent upvotes like -1
        ]);
    }

    /**
     * @Route("/action/comment/{entityId}/{toUserId}", name="comment", methods={ "POST" })
     */
    public function comment(int $entityId, int $toUserId, Request $request): JsonResponse
    {
        $commentData = $request->request->all();

        if (empty($commentData['comment']))
            return new JsonResponse([ 'status' => 'failure' ]);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $userAction = new Action();
        $userAction->setEntityId($entityId)
                    ->setUserId($user->getUserId())
                    ->setToUserId($toUserId)
                    ->setActionDate(new \DateTime())
                    ->setEntityType('post')
                    ->setActionType('comment')
                    ->setContent($commentData['comment']);
        $em->persist($userAction);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'actualUserFullName' => $user->getFirstName() . ' ' . $user->getLastName(),
            'actualUserLink' => $this->generateUrl('view_user', [ 'permalink' => $user->getPermalink() ]),
            'actualUserProfilePic' => $user->getProfilePic()
        ]);
    }
}