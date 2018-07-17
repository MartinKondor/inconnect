<?php

namespace App\Controller;

use App\Entity\{ ICUser, Post, Action };

use PHPUnit\Util\Json;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{ Response, JsonResponse, Request };

class MessageController extends Controller
{
    /**
     * @Route("/messages", name="messages", methods={ "GET", "POST" })
     */
    public function messages()
    {
        $user = $this->getUser();

        $connection = $this->getDoctrine()->getManager()->getConnection();
        $contactsQuery = $connection->prepare("SELECT * FROM friend
                                LEFT JOIN icuser
                                ON friend.to_user_id = icuser.user_id
                                WHERE friend.from_user_id = :user_id
                                AND friend.status = 'friends'");
        $contactsQuery->execute([
            ':user_id' => $user->getUserId()
        ]);
        $contacts = $contactsQuery->fetchAll();

        return $this->render('users/messages.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/messages/send/{toUserId}", name="send_message", methods={ "POST" })
     */
    public function sendMessage($toUserId)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $message = new Action();
        $message->setEntityId($toUserId)
            ->setUserId($user->getUserId())
            ->setToUserId($toUserId)
            ->setActionDate(new \DateTime())
            ->setEntityType('message')
            ->setActionType('message')
            ->setContent($_POST['message']);

        $em->persist($message);
        $em->flush();

        return new Response('success');
    }

    /**
     * @Route("/messages/get", name="get_messages", methods={ "POST" })
     */
    public function getMessages()
    {
        // List messages from a specific user in json
        $user = $this->getUser();

        $connection = $this->getDoctrine()->getManager()->getConnection();
        $msgQuery = $connection->prepare("SELECT icuser.user_id, `action`.`action_type`, `action`.`entity_type`,
                                        `action`.`user_id`, `action`.`content`, `action`.`to_user_id`,
                                        `action`.`action_date`, icuser.first_name,
                                        icuser.last_name
                                        FROM `action`
                                        LEFT JOIN icuser
                                        ON icuser.user_id = `action`.`user_id`
                                        WHERE `action`.`entity_type` = 'message'
                                        AND `action`.`action_type` = 'message'
                                        AND ((`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id) OR (`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id))
                                        OR (`action`.`to_user_id` = :user_id AND `action`.`user_id` = :from_user_id)
                                        OR (`action`.`to_user_id` = :from_user_id AND `action`.`user_id` = :user_id)");
        $msgQuery->execute([
            ':user_id' => $user->getUserId(),
            ':from_user_id' => $_POST['fromUserId']
        ]);
        $messagesBetweenUsers = $msgQuery->fetchAll();

        return new JsonResponse([
            'messages' => $messagesBetweenUsers
        ]);
    }
}