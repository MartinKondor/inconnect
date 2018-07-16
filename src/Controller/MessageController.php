<?php

namespace App\Controller;

use App\Entity\{ ICUser, Post, Action };

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
     * @Route("/messages/get/{fromUserId}", name="get_messages", methods={ "POST" })
     */
    public function getMessages($fromUserId)
    {
        // List messages from a specific user in json

        //SELECT * FROM `action`
        //LEFT JOIN icuser ON icuser.user_id = `action`.`user_id`
        //WHERE `action`.`entity_type` = 'message'
        //AND `action`.`action_type` = 'message'
    }
}